<?php

namespace App\Models;

use App\SetupCrubApi\ImageQuery;
use App\SetupCrubApi\StringQuery;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'vote',
        'vote_comment',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function follower()
    {
        return $this->hasOne(Follower::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }
    public function friend()
    {
        return $this->hasOne(Friend::class, 'user_id');
    }
    public function seri()
    {
        return $this->hasMany(Seri::class, 'user_id');
    }
    public function post()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function getColumn()
    {
        return [
            'name' => StringQuery::run(),
            'email' => StringQuery::run(),
            'password' => StringQuery::run(),
            'image' => ImageQuery::run(),
            'vote' => StringQuery::run(),
            'vote_comment' => StringQuery::run(),
        ];
    }
}