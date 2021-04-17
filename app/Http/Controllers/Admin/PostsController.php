<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Scopes\PublishedScope;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::withoutGlobalScope(PublishedScope::class)
            ->leftJoin('categories', 'categories.id', '=', 'posts.category_id')
            ->select([
                'posts.*',
                'categories.name as category_name'
            ])
            //->orderBy('created_at', 'DESC')
            ->latest()
            ->paginate();

        return view('admin.posts.index', [
            'posts' => $posts,
            'total_posts' => Post::count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create', [
            'post' => new Post(),
            'categories' => Category::all(),
            'tags' => Tag::all(),
            'post_tags' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate( $this->rules() );

        $image_path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $file->getClientOriginalName();
            $file->getClientOriginalExtension(); // 'jpg'
            $file->getSize();
            $file->getMimeType(); // image/jpeg, image/png

            if ($file->isValid()) {
                $image_path = $file->storeAs('uploads', $file->getClientOriginalName(), [
                    'disk' => 'public',
                ]);
            }
        }

        $post = Post::create([
            'title' => $request->post('title'),
            //'slug' => Str::slug($request->post('title')),
            'content' => $request->post('content'),
            'category_id' => $request->post('category_id'),
            'image' => $image_path,
        ]);

        $post->tags()->attach($request->post('tag'));

        return redirect()->route('admin.posts.index')->with('success', 'Post created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::withTrashed()->withoutGlobalScope('published')->findOrFail($id);

        $post_tags = $post->tags()->pluck('id')->toArray();

        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::all(),
            'tags' => Tag::all(),
            'post_tags' => $post_tags,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate( $this->rules() );

        $post = Post::withoutGlobalScope('published')->findOrFail($id);

        $image_path = null;
        $old_image = $post->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $image_path = $file->storePublicly('uploads', [
                    'disk' => 'public',
                ]);
            }
        }

        $post->update([
            'title' => $request->post('title'),
            //'slug' => Str::slug($request->post('title')),
            'content' => $request->post('content'),
            'category_id' => $request->post('category_id'),
            'image' => $image_path? $image_path : $post->image,
        ]);

        if ($image_path && !empty($old_image)) {
            Storage::disk('public')->delete($old_image);
        }

        $post->tags()->sync($request->post('tag'));

        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::withoutGlobalScope('published')->findOrFail($id);
        $post->delete();

        /*if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }*/

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }

    public function download($id)
    {
        $post = Post::findOrFail($id);
        if (!$post->image) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $post->image));
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|int|exists:categories,id',
            'content' => 'required',
            'image' => 'image|max:409600'
        ];
    }
    
    public function trash()
    {
        $posts = Post::onlyTrashed()->paginate();
        return view('admin.posts.trash', [
            'posts' => $posts,
        ]);
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('admin.posts.index')->with('success', 'Post restored.');
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();

        /*if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }*/

        return redirect()->route('admin.posts.index')->with('success', 'Post force deleted.');
    }
}
