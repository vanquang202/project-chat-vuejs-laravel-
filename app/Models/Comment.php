<?php

namespace App\Models;

use App\Casts\FormatDate;
use App\Services\ApiManager\ApiCrubService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory , ApiCrubService;

    protected $table = 'comment';
    protected $guarded = [];
    protected $casts = [
      'created_at' => FormatDate::class
    ];
    public function user(){
        return $this -> belongsTo(User::class);
    }
    public function comment(){
        return $this -> hasMany(Comment::class) -> with('user');
    }
}
