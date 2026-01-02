<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'comments.user'])->latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Search for posts.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $posts = Post::where('content', 'like', "%{$query}%")
                    ->with(['user', 'likes', 'comments.user'])
                    ->latest()
                    ->get();
        
        return view('posts.index', compact('posts'));
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
        $request->validate([
            'content' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $sourceImage = null;
            $extension = strtolower($file->getClientOriginalExtension());
            $gdAvailable = extension_loaded('gd');

            // Proceed with resizing ONLY if GD is available
            if ($gdAvailable) {
                // Create image resource from file
                switch ($extension) {
                    case 'jpeg':
                    case 'jpg':
                        if (function_exists('imagecreatefromjpeg')) {
                            $sourceImage = \imagecreatefromjpeg($file->getRealPath());
                        }
                        break;
                    case 'png':
                        if (function_exists('imagecreatefrompng')) {
                            $sourceImage = \imagecreatefrompng($file->getRealPath());
                        }
                        break;
                    case 'gif':
                        if (function_exists('imagecreatefromgif')) {
                            $sourceImage = \imagecreatefromgif($file->getRealPath());
                        }
                        break;
                }

                if ($sourceImage) {
                    // Get original dimensions
                    $width = \imagesx($sourceImage);
                    $height = \imagesy($sourceImage);
                    $maxDim = 1024;

                    // Calculate new dimensions if resizing is needed
                    if ($width > $maxDim || $height > $maxDim) {
                        $ratio = $width / $height;
                        if ($ratio > 1) {
                            $newWidth = $maxDim;
                            $newHeight = $maxDim / $ratio;
                        } else {
                            $newHeight = $maxDim;
                            $newWidth = $maxDim * $ratio;
                        }

                        $newnet = \imagecreatetruecolor($newWidth, $newHeight);

                        // Preserve transparency for PNG/GIF
                        if ($extension == 'png' || $extension == 'gif') {
                            \imagecolortransparent($newnet, \imagecolorallocatealpha($newnet, 0, 0, 0, 127));
                            \imagealphablending($newnet, false);
                            \imagesavealpha($newnet, true);
                        }

                        \imagecopyresampled($newnet, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        
                        // Save resized image to temporary path
                        $fileName = 'posts/' . \uniqid() . '.' . $extension;
                        $tempPath = \sys_get_temp_dir() . '/' . \basename($fileName);
                        
                        switch ($extension) {
                            case 'jpeg':
                            case 'jpg':
                                \imagejpeg($newnet, $tempPath, 85);
                                break;
                            case 'png':
                                \imagepng($newnet, $tempPath);
                                break;
                            case 'gif':
                                \imagegif($newnet, $tempPath);
                                break;
                        }
                        
                        // Store the resized image using Laravel's Storage
                        $imagePath = \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('posts', new \Illuminate\Http\File($tempPath), \basename($fileName));
                        
                        \imagedestroy($sourceImage);
                        \imagedestroy($newnet);
                    } else {
                        // No resizing needed
                        $imagePath = $file->store('posts', 'public');
                    }
                } else {
                    // Fallback if source image creation failed
                    $imagePath = $file->store('posts', 'public');
                }
            } else {
                // Fallback if GD is not loaded
                $imagePath = $file->store('posts', 'public');
            }
        }

        Post::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'image_path' => $imagePath,
        ]);
        
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
         return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post->update([
            'content' => $request->content
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        
        $post->delete();
        return back();
    }
}
