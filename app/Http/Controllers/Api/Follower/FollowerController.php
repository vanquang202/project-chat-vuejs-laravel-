<?php

namespace App\Http\Controllers\Api\Follower;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    private $follower;

    public function __construct(Follower $follower)
    {
        $this -> follower = $follower;
    }
    public function addFollower($id)
    {
       try {
            $user = $this-> follower::where('user_id' , $id) -> first();
            if(!$user) $user = $this -> follower::create([
                'user_id' => $id ,
            ]);
            if(!in_array(auth() -> user() -> id , $user -> folower ?? []))
            {
                $folower = $user -> folower ?? [];
                array_push($folower , auth() -> user() -> id);
                $user -> folower = $folower;
                $user -> save();
            }
            return response(['code' => 200]);
       } catch (\Throwable $th) {
           echo $th;
        return response(['code' => 502]);
       }
    }
    public function unFollower($id)
    {
        try {
            $user = $this-> follower::where('user_id' , $id) -> first();
            if(in_array(auth() -> user() -> id , $user -> folower ?? []))
            {
                $user -> folower = array_filter($user -> folower ?? [],function ($u) {
                    return $u !== auth() -> user() -> id ;
                });
                $user -> save();
            }
            return response(['code' => 200]);
        } catch (\Throwable $th) {

            return response(['code' => 502]);
        }
    }
}
