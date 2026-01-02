<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;




// Home page (welcome)
Route::get('/',[PostController::class, 'index' ]);

// Posts CRUD + search
Route::get('/posts/search', [PostController::class, 'search'])
     ->name('posts.search')
     ->middleware('auth');
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index')->middleware('auth');
Route::delete('/notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('auth');
Route::resource('posts', PostController::class);

// Comments CRUD (store, destroy)
Route::resource('comments', CommentController::class)->only(['store', 'destroy']);

// Likes toggle
Route::post('/likes', [LikeController::class, 'store'])
     ->name('likes.store')
     ->middleware('auth');

Route::post('/save', [App\Http\Controllers\SaveController::class, 'store'])
     ->name('save.store')
     ->middleware('auth');

// Profile page + search
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/profile/search', [ProfileController::class, 'search'])
     ->name('profile.search')
     ->middleware('auth');

require __DIR__.'/auth.php';

// Temporary Test Route for Notifications
Route::get('/test-notification', function () {
    \App\Models\Notification::create([
        'user_id' => auth()->id(),
        'actor_id' => auth()->id(), // Self for testing
        'type' => 'test',
        'data' => ['message' => 'This is a test notification to prove it works!'],
    ]);
    return redirect()->route('notifications.index');
})->middleware('auth');
