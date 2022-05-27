<?php

namespace App\SetupCrubApi;

class ImageQuery extends SetupCrubApi
{
    public function execute(): string
    {
        
        $nameImage = uniqid() . '.' . $this -> value -> getClientOriginalExtension();
        $this -> value -> move(public_path('images'), $nameImage); 

        return $nameImage;
    }
}
