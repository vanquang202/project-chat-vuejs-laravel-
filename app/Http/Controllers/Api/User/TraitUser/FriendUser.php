<?php

namespace App\Http\Controllers\Api\User\TraitUser;

use App\Events\NotificationC;
use App\Events\SendChat;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait FriendUser
{
    public function getAllUSersDeloyFriend(Request $request)
    {
        $data = [];
        $friend = Friend::where('user_id', Auth::user() -> id )-> first();
        $listFriend = explode(',', $friend -> friend ?? '');
        $listDeloyFriend = explode(',', $friend -> deloy_friend ?? '');

        if($request->has('name')){
            $userSearch = $this -> user::where('name' , 'like' , '%' .$request -> name.'%')->get();
            foreach ($userSearch as $user)
            {
                if($user->id == Auth::user()->id){ continue; }
                if(!in_array($user->id, $listFriend)) {

                    $i = 0;
                    $listDeloyFriendYou = explode(',', $user -> friend -> deloy_friend ?? '');
                    if(in_array(Auth::user()->id, $listDeloyFriendYou))
                    {
                        $i = 1;
                    }
                    if(!in_array($user->id, $listDeloyFriend))
                    {
                        $userR = [
                            'user' => $user ,
                            'status' => $i
                        ];
                        array_push($data, $userR);
                    }

                };

            }
            return response()->json([
                'code' => 200,
                'data' => $data
            ]);
        }

        $users = User::all()->random(10);
        foreach ($users as $user)
        {
            if($user->id == Auth::user()->id){ continue; }
            if(!in_array($user->id, $listFriend)) {

                $i = 0;
                $listDeloyFriendYou = explode(',', $user -> friend -> deloy_friend ?? '');
                if(in_array(Auth::user()->id, $listDeloyFriendYou))
                {
                    $i = 1;
                }
                if(!in_array($user->id, $listDeloyFriend))
                {
                    $userR = [
                        'user' => $user ,
                        'status' => $i
                    ];
                    array_push($data, $userR);
                }

            };

        }
        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }

    public function addAllUSersDeloyFriend(Request $request )
    {
        if(!$this->friend->where('user_id' , $request -> id) -> exists()){
            $this->friend::create([
                'user_id' => $request -> id,
            ]);
        }
//        return  $request ->id ;
        $friend = $this->friend->where('user_id' , $request -> id)-> first();
        $listDeloyFriendArr = explode(',', $friend -> deloy_friend);
        $listDeloyFriend = '';
        if(in_array(auth() -> user() -> id , $listDeloyFriendArr)){
            foreach($listDeloyFriendArr as $l){
                if(auth() -> user() -> id != $l){
                    $listDeloyFriend .= $l . ',';
                }
            }
        }else{
            $listDeloyFriend = $friend->deloy_friend .','. auth() -> user() -> id ;
        }
        $listDeloyFriend = rtrim($listDeloyFriend,",");
        $friend -> deloy_friend = $listDeloyFriend;
        $friend -> save();
        event(new NotificationC( $request -> id ));

    }

    public function getAllUSersDeloyMe()
    {
        $data = [];
        $friend = $this->friend::where('user_id', Auth::user() -> id )-> first();
        $textList = ltrim($friend -> deloy_friend ?? '',",");
        $listDeloy =  explode(',', $textList);

        foreach ($listDeloy as $l){
            array_push($data,$this -> user::find($l));
        };

        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }

    public function addAllUSersDeloyMe(Request $request)
    {
        $id =  auth() -> user() -> id ;
        //Me
        $friend = auth() -> user() -> friend;
        $listFriend =  $friend-> friend ;
        $listFriendDeloy = explode(',', $friend-> deloy_friend ?? []);
        $friend -> deloy_friend = $this->removeDeloy($listFriendDeloy , $request->id);
        $friend -> friend = $listFriend . ','. $request->id;
        $friend -> save();

        //You

        if(!$this -> friend::where('user_id',$request->id) -> exists()){
            $this -> friend::create([
                'user_id' => $request->id,
            ]);
        }
        $friednYou = $this -> friend::where('user_id',$request->id)->first();
        $listFriendYou = ( $friednYou-> friend ?? ''). ','. $id;
        $listFriendYou = ltrim($listFriendYou,",");
        $listFriendDeloyYou = explode(',', $friednYou-> deloy_friend ?? '');
        $friednYou -> deloy_friend = $this->removeDeloy($listFriendDeloyYou , $id);
        $friednYou -> friend = $listFriendYou;
        $friednYou -> save();

        event(new NotificationC( $request -> id ));
        event(new NotificationC( auth() -> user() -> id ));
        event(new SendChat(Auth::user()->id));
        return response([
            'code' => 200,
        ]);
    }
    public function removeAllUSersDeloyMe(Request $request)
    {
        //Me
        $id =  auth() -> user() -> id ;
        $friend = auth() -> user() -> friend;
        $listFriend =  explode(',',$friend-> friend ?? []) ;
        $listFriendDeloy = explode(',', $friend-> deloy_friend ?? []);
        $friend -> deloy_friend = $this->removeDeloy($listFriendDeloy , $request->id);
        $friend -> friend =$this->removeDeloy($listFriend , $request->id);
        $friend -> save();

        //You
        $friednYou = $this -> friend::where('user_id',$request->id)->first();
        $listFriendYou =  explode(',',$friednYou-> friend ?? []) ;
        $listFriendDeloyYou = explode(',', $friednYou-> deloy_friend ?? []);
        $friend -> deloy_friend = $this->removeDeloy($listFriendDeloy , $id);
        $friend -> friend =$this->removeDeloy($listFriend ,$id);
        $friednYou -> save();

        event(new NotificationC( $request -> id ));
        event(new NotificationC( auth() -> user() -> id ));
        return response([
            'code' => 200,
        ]);
    }
    public function removeDeloy($listFriendDeloy , $id)
    {
        $text = '';
        foreach($listFriendDeloy as $l)
        {
            if($l != $id)
            {
                $text .= $l .',';
            }
        }
        $text = rtrim($text,",");
        return $text;
    }
}
