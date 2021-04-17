<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class CategoriesController extends Controller
{

    public function index()
    {
        // Collection (imagine as array)
        $categories = Category::leftJoin('categories as parent', 'parent.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parent.name as parent_name',
                DB::raw('(SELECT COUNT(*) FROM posts WHERE posts.category_id = categories.id) as products_count'),
            ])
            //->selectRaw('(SELECT COUNT(*) FROM posts WHERE posts.category_id = categories.id) as products_count')
            ->orderBy('name', 'ASC')
            ->paginate(5);

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $parent_categories = Category::all();
        return view('admin.categories.create', [
            'parent_categories' => $parent_categories,
            'category' => new Category(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3|unique:categories,name',
            'parent_id' => 'nullable|int|exists:categories,id',
        ]);
        /*if ($validator->fails()) {
            dd( $validator->errors() );
        }*/
        $validator->validate();

        $category = new Category();
        $category->name = $request->post('name');
        $category->slug = Str::slug($request->post('name'));
        $category->parent_id = $request->post('parent_id');

        $category->save();

        // PRG
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created!');
    }

    public function edit($id)
    {
        // SELECT * FROM categories WHERE id = $id
        $category = Category::findOrFail($id);

        $parent_categories = Category::where('id', '<>', $id)->get();

        return view('admin.categories.edit', [
            'category' => $category,
            'parent_categories' => $parent_categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category == null) {
            abort(404);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                //(new Unique('categories', 'name'))->ignore($id), // 'unique:categories,name,' . $id,
                Rule::unique('categories', 'name')->ignore($id),
            ],
            'parent_id' => 'nullable|int|exists:categories,id',
        ]);

        $category->name = $request->post('name');
        $category->slug = Str::slug($request->post('name'));
        $category->parent_id = $request->post('parent_id');

        $category->save();

        // PRG
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated!');
    }

    public function destroy($id)
    {
        // Method 1
        //$category = Category::findOrFail($id);
        //$category->delete();

        // Method 2
        //Category::where('id', '=', $id)->delete();

        // Method 3
        Category::destroy($id);

        // PRG
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted!');
    }
}
