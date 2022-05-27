<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;
    protected $table = 'folowers';
    protected $guarded = [];

    public function getFolowerAttribute($value){
        return json_decode($value);
    }
}
