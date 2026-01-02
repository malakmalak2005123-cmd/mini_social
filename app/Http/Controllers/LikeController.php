<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like for a post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $existingLike = Like::where('user_id', auth()->id())
                            ->where('post_id', $request->post_id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'post_id' => $request->post_id,
            ]);

            // Notify Post Owner
            $post = \App\Models\Post::find($request->post_id);
            if ($post->user_id !== auth()->id()) {
                \App\Models\Notification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => auth()->id(),
                    'type' => 'like',
                    'data' => [
                        'post_id' => $post->id,
                        'message' => auth()->user()->name . ' liked your post.'
                    ],
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'liked' => !$existingLike, // If it was existing and we deleted it, current state is unliked (false)
                'count' => \App\Models\Post::find($request->post_id)->likes()->count(),
            ]);
        }

        return back();
    }
}
