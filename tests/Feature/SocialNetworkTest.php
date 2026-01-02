<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialNetworkTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_feed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/posts');

        $response->assertStatus(200);
        $response->assertSee('Home');
    }

    public function test_user_can_create_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Hello World!',
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', [
            'content' => 'Hello World!',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_post_with_comments()
    {
        $user = User::factory()->create();
        $post = Post::create([
            'content' => 'Test Post',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertSee('Test Post');
    }

    public function test_user_can_comment_on_post()
    {
        $user = User::factory()->create();
        $post = Post::create([
            'content' => 'Test Post',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post('/comments', [
            'content' => 'Nice post!',
            'post_id' => $post->id,
        ]);

        $response->assertStatus(302); // back() typically redirects
        $this->assertDatabaseHas('comments', [
            'content' => 'Nice post!',
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_like_post()
    {
        $user = User::factory()->create();
        $post = Post::create([
            'content' => 'Test Post',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post('/likes', [
            'post_id' => $post->id,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        // Toggle unlike
        $response = $this->actingAs($user)->post('/likes', [
            'post_id' => $post->id,
        ]);

        $this->assertDatabaseMissing('likes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_search_posts()
    {
        $user = User::factory()->create();
        Post::create(['content' => 'UniqueKeyword', 'user_id' => $user->id]);
        Post::create(['content' => 'Other content', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/posts/search?query=UniqueKeyword');

        $response->assertStatus(200);
        $response->assertSee('UniqueKeyword');
        $response->assertDontSee('Other content');
    }

    public function test_user_can_view_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/profile');
        
        $response->assertStatus(200);
        $response->assertSee($user->name);
    }
}
