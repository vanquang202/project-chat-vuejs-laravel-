<?php

namespace App\Services\Query;

class Tag extends Query
{
    public function run($q){
        return $q -> where('tag' , 'like' , '%'. str_replace( "-", " ",request($this -> getClass())) .'%');
    }
}
