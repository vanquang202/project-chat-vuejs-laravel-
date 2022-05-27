<?php

namespace App\Http\Controllers\Api\Room\TraitRoom;

use App\Events\NotificationC;
use App\Events\NotifiSendRoom;
use App\Events\SendChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait ChatRoom
{


    public function store(Request $request)
    {

        $dt = Carbon::now('Asia/Ho_Chi_Minh');

        $data =str_replace('-',' ',$request->data);
        $data =str_replace(',',' ',$data);
        $id_fr = $request-> id ;
        $room_user = $id_fr * Auth::user()->id;
        $room = $this -> room::where('room_user' , $room_user)->first() ?? $this -> room::create(['room_user' => $room_user]);
        $textImage = $room -> data ;
        if($request -> has('image')){

            $request -> validate([ 'image' => 'image' ]);
            $nameImage = uniqid() . '.' . $request -> image -> getClientOriginalExtension();
            $request -> image -> move(public_path('images') , $nameImage);
            $textImage = $textImage .','. Auth::user()->id . '--' . $nameImage .'--' .$dt->toDateTimeString().'--0';

        }
        if($request -> has('data')){
            $text = $textImage .','. Auth::user()->id . '--' . $data .'--' .$dt->toDateTimeString().'--0';
        }else{
            $text = $textImage;
        }

        $room -> data = $text;
        $room -> save();

        // event(new SendChat(Auth::user()->id));
        event(new SendChat($id_fr));
        event(new NotificationC($id_fr));
    }


    public function show($room)
    {
        $id_fr = $room;
        $arr = [];
        $arrD = [];

        $room_user = $room * Auth::user()->id;
        $listFriend = Auth::user()->friend->friend;
        $arrCheck = explode(',', $listFriend);
        if(!in_array($room , $arrCheck)) return response(['code' => 500]);

        $room =  $this->room::where('room_user' , $room_user)->first() ?? $this -> room::create(['room_user' => $room_user]);
        $roomUpdate = $room ;

        $dataUpdate = explode(',', $roomUpdate -> data ) ?? [];
        $text = '';
        foreach ($dataUpdate as $value) {
            $list = explode("--", $value ) ?? [];

            if(count($list) >= 2) {
                $i = $list[3];
                if(reset($list) != Auth::user()->id)
                {
                    $i = 1;
                }
                $text .= $list[0] .'--'.$list[1] .'--'.$list[2] .'--'.$i .',';
            }
        }
        $text = rtrim($text,",");
        $roomUpdate -> data = $text;
        $roomUpdate -> save();
        $data = explode(',', $text ) ?? [];
        $data =  array_chunk(array_reverse($data) , (request('page') ?? 1) * 20);

        foreach (array_reverse($data[0]) as $value) {

            $list = explode("--", $value ) ?? [];

            if(count($list) >= 2) {
                $err = [];
                // $img = explode("." ,$list[1]);
                $flag = 0;
                $view = 0;

                if(file_exists(public_path('images') . '\\' . $list[1] )) $flag = 1 ;
                if(file_exists(public_path('files') . '\\' . $list[1] )) $flag = 2 ;
                if($list[3] == 1) $view = 1 ;

                $err[reset($list)] = [
                    'user'  => $this->user::find(reset($list) , ['name' , 'email' , 'image']) ,
                    'data'  => $list[1] ,
                    'img'   => $flag ,
                    'view'  => $view ,
                    'time'  => Carbon::parse(($list[2]))->diffForHumans() ,
                    'me_id' => reset($list) ,
                ];

//                array_push($arrD,$err);
                $arrD = array_merge($arrD,$err);
            }

        }

        event(new NotificationC(auth()->user() -> id ));
        return response()->json([
            'code' => 200,
            'data' => $arrD,
            'friend' => $this -> user::find($id_fr,['name','image']),
        ]);
    }
}
