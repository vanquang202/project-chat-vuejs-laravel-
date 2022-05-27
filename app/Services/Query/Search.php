<?php

namespace App\Services\Query;

class Search extends Query
{
    public function run($q){
        return $q -> where('title' , 'like' , '%'.request($this -> getClass()).'%') ->  orWhere('tag' , 'like' , '%'.request($this -> getClass()).'%');
    }
}
