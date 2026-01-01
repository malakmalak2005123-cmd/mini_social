<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts=Post::with('user','likes','comments')->get();
        return view('posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Post::create([
            'content'=>$request->content,
            'user_id'=>auth()->id(),
        ]);
        return redirected()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, Post $post)
{
    $post->update([
        'content' => $request->content
    ]);

    return redirect()->route('posts.index');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
{
    $post->delete();
    return redirect()->route('posts.index');
}

}
