<?php

namespace App\Services\Query;

class Query
{
    public  function  handle($request, \Closure $next){
        if(request() -> has($this -> getClass())){
            return $this -> run($next($request));
        }
        return $next($request);
    }
    public function getClass(){
        return \Str::lower(class_basename($this));
    }
}
