<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
                    ? asset('storage/' . $this->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function posts(){
        return $this->hasMany(Post::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function likes(){
        return $this->hasMany(Like::class);
    }
    public function savedPosts(){
        return $this->belongsToMany(Post::class, 'saved_posts', 'user_id', 'post_id')->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at')->latest();
    }
}
