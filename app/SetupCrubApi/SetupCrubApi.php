<?php

namespace App\SetupCrubApi;

abstract class SetupCrubApi
{
    protected $value;
    static function run(): SetupCrubApi
    { 
        return static;
    }

    public function setValue(mixed $value): SetupCrubApi
    {
         $this -> value = $value ;
         return $this;
    }

    public function execute(): string
    {
        return $this -> value;
    }
}
