<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
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
        $posts = Post::all();
        return view('admin.posts.index', [
            'posts' => $posts
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
            'slug' => Str::slug($request->post('title')),
            'content' => $request->post('content'),
            'category_id' => $request->post('category_id'),
            'image' => $image_path,
        ]);

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
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => Category::all(),
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

        $post = Post::findOrFail($id);

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
            'slug' => Str::slug($request->post('title')),
            'content' => $request->post('content'),
            'category_id' => $request->post('category_id'),
            'image' => $image_path? $image_path : $post->image,
        ]);

        if ($image_path && !empty($old_image)) {
            Storage::disk('public')->delete($old_image);
        }

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
        $post = Post::findOrFail($id);
        $post->delete();

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

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
            'category_id' => 'required|int|exists:categories,id',
            'content' => 'required',
            'image' => 'image|max:409600'
        ];
    }
}
