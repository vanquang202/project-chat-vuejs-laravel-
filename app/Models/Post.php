<?php

namespace App\Models;


use App\Casts\FormatDate;
use App\Services\ApiManager\ApiCrubService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory , ApiCrubService;

    protected $table = 'posts';
    protected $guarded = [];
    protected $casts = [
        'created_at' => FormatDate::class
    ];
    protected $appends = [
        'slug_title',
        'slug_tag',
    ];
    public function getSlugTitleAttribute(){
         return \Str::slug( $this -> title);
    }
    public function getSlugTagAttribute(){
        $list = explode(',', ($this -> tag));
        $data = [];
        array_walk($list, function($l) use (&$data) {
          array_push($data , \Str::slug($l)) ;
        });
        return $data;
    }
    public function user(){
        return $this -> belongsTo(User::class);
    }
    public function seri(){
        return $this -> belongsTo(Seri::class , 'seri_id');
    }
    public function comment(){
        return $this -> hasMany(Comment::class)
                    -> where('comment_id' , 0)
                    -> with(['user' , 'comment'])
                    ->orderBy('id','desc' );
    }
}
