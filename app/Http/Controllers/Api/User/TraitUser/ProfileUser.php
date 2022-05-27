<?php

namespace App\Http\Controllers\Api\User\TraitUser;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

trait ProfileUser
{
    public function getMe()
    {
        $user = $this -> user::with('follower')->find(auth() -> id()) ;
        if(auth()->check()){
            return response()->json([
                'code'    => 200,
                'user'    => $user,
                'details' => auth() -> user() -> profile -> detail ?? '',
            ]);
        }   else{
            return response()->json([
                'code'    => 500,
                'message' => 'Error',
            ]);
        }
    }

    public function setMe(Request $request)
    {
        $user =  User::find(auth()->user()->id );
        $nameImage = $user->image ;
        if($request->has('image')){
            if(file_exists(public_path('images\\'.$user->image)) && $user->image != null && $user->image != 'default.png'){
                unlink(public_path('images\\'.$user->image));
            }
            $nameImage = uniqid() . '.'. $request -> image -> getClientOriginalExtension();
            $request -> image -> move('images' , $nameImage);
            $user->update([
                'name' => $request -> name,
                'image' => $nameImage,
            ]);
        }{
        $user->update([
            'name' => $request -> name
        ]);
    }

        if(auth()->user()->profile){

            $this->profile::where('user_id', auth()->user()->id)->update([
                'detail' => $request -> details,
            ]);

        }else{

            $this->profile::create([
                'detail'  => $request -> details,
                'user_id' => auth()->user()->id
            ]);

        }
        return response()->json([
            'code' => 200,
            'imageNew' => $nameImage,
        ]);
    }

    public function getYou($id){
        $flag = false;
        $user = $this -> user::where('id', $id,['id','name','email','image'])->with('follower')->first();
        if(in_array( auth() -> user() -> id , $user->follower->folower ?? [])) $flag = true;

        return response()->json([
            'code' => 200,
            'you'  => $user,
            'details' => $user->profile-> detail?? '',
            'flag_follower' => $flag
        ]);

    }
}
