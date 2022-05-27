<?php

namespace App\Models;

use App\Services\ApiManager\ApiCrubService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seri extends Model
{
    use HasFactory , ApiCrubService;

    protected $table = 'seris';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($q){
            $q -> post() -> update(['seri_id' => null]);
        });
    }

    public function post(){
        return $this -> hasMany(Post::class);
    }
    public function user(){
        return $this -> belongsTo(User::class);
    }
}
