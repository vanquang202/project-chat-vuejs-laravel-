<?php

namespace App\Http\Controllers\Api\Room\TraitRoom;

use App\Events\NotificationC;
use App\Events\NotifiSendRoom;
use App\Events\RedirectUser;
use App\Events\SendChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

trait ChatRoomGruop
{
    public function createRoom(Request $request)
    {
//        if($this -> room_groups::where('name_room',$request->name))
        $code =   uniqid() . time();
        $code = substr($code , 0 ,20);
        if($this -> room_groups::where('code_room',$code)->exists()){
            $code = uniqid() . time();
            $code = substr($code , 0 ,20);
        }
        $roomNew = $this -> room_groups::create([
            'name_room'  => $request->name,
            'boss'      =>  auth()->user()->id,
            'content_chat' => auth()->user()->id .'-- '.auth()->user() -> name .' đã tạo nhóm --' . Carbon::now() -> toDateTimeString() . '--'. auth()->user()->id,
            'code_room' => $code,
        ]);
        event(new NotificationC(auth()->user() -> id ));
        return response([
            'code' => 200,
            'code_room' => $code,
            'id' => $roomNew->id
        ]);
    }

    public function getRoom($code)
    {
        $id = auth()->user()->id;
        $arrD = [];
        $room_gruop = $this -> room_groups::where('code_room' , $code)->first();
        $textPeoPleListA = rtrim($room_gruop -> people,',');
        $checkPeoPle = explode(',',$textPeoPleListA);
        if($id != $room_gruop -> boss) if(!in_array($id , $checkPeoPle)  ) return response(['code' => 500]);
        $roomSave = $room_gruop ;
        $textSaveAsF = '';
        $textGetEx = ltrim($roomSave-> content_chat ?? '',",");
        $listTextSave = explode(",", $textGetEx ?? '');
        if(($listTextSave != [""])){

            foreach ($listTextSave as $r)
            {
                $list = explode('--', $r ?? '');
                if(count($list) > 2){
                    $textUs = $list[3]  ;
                    $listUserIcon = explode("-" ,$list[3]  );
                    if(!in_array($id , $listUserIcon))
                    {
                        $textUs .= '-'.$id;
                    }
                    $textSaveAsF .= $list[0].'--'.$list[1].'--'.$list[2].'--'.$textUs.',';
                }

            }
            $textSaveAsF = rtrim($textSaveAsF,",");
            $roomSave -> content_chat = $textSaveAsF ;
            $roomSave -> save();
        }

        $listChat =  explode(',' , $room_gruop -> content_chat);

        $listChat =  array_chunk(array_reverse($listChat) , (request('page') ?? 1) * 20);
        foreach(array_reverse($listChat[0]) as $chat)
        {
            $list = explode('--', $chat ?? '');
            if(count($list) >= 2) {
                $err = [];
                $img = explode("." ,$list[1]);
                $listUserIcon = explode("-" ,$list[3]);
                $flag = 0;
                $userList = [];
                foreach($listUserIcon as $l){
                    array_push($userList , $this -> user::find(($l) , [ 'image']) ?? 0)   ;
                }

                if(file_exists(public_path('images') . '\\' . $list[1] )) $flag = 1 ;
                if(file_exists(public_path('files') . '\\' . $list[1] )) $flag = 2 ;

                $err[reset($list)] = [
                    'user'  => $this -> user::find(reset($list) , ['name' , 'email' , 'image']) ,
                    'data'  => $list[1] ,
                    'img'   => $flag ,
                    'view'  => $userList ,
                    'time'  => Carbon::parse(($list[2]))->diffForHumans() ,
                    'me_id' => reset($list) ,
                ];

//                array_push($arrD,$err);
                $arrD = array_merge($arrD,$err);
            }

        }
        event(new NotificationC(auth()->user() -> id ));
        return response()->json([
            'code'       => 200,
            'data'       => $arrD,
            'roomDatils' => $room_gruop
        ]);
    }

    public function chatRoomSendData(Request $request)
    {
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $id = Auth::user()->id ;
        $code = $request-> code ;
        $textImage = '';
        $textChat = '';
        $room_gr = $this->room_groups::where('code_room', $code)->first();
        $grId = $room_gr->id ;
//        $textChat = $room_gr-> content_chat;

        if($request -> has('image')){
            $request -> validate([ 'image' => 'image' ]);
            $nameImage = uniqid() . '.' . $request -> image -> getClientOriginalExtension();
            $request -> image -> move(public_path('images') , $nameImage);
            $textImage =  $id . '--' . $nameImage .'--' .$dt->toDateTimeString().'--'.$id.',';
        }

        if($request -> has('data')) {
            $dataText = str_replace('-',' ',$request->data);
            $textChat =  $id . '--' . $dataText.'--' .$dt->toDateTimeString().'--'.$id;
        }
        $textRTrim = rtrim($room_gr -> content_chat);
        $textSave =$textRTrim.','.$textImage.$textChat;
        $textSave = rtrim($textSave . ",");
        $textSave = ltrim($textSave,",");
        $room_gr -> content_chat = $textSave ;
        $room_gr -> save();

        // event(new SendChat(Auth::user()->id));
        event(new NotifiSendRoom($grId));
        event(new NotificationC($grId));
    }


    public function changePassRoom(Request $request)
    {

        $room = $this -> room_groups::where('code_room',$request->code)->first();
        if($room -> boss != auth() -> user()->id) { return response(['code' => 500]);}
        $room -> password = bcrypt($request -> pass);
        $room -> save();
        event(new SendChat(Auth::user()->id));
        return response(['code' => 200]);

    }

    public function searchRoom(Request $request)
    {
        $flag = 0;
        $flagPass = 0;
        if($room = $this -> room_groups::where('code_room',$request -> data) -> first()){
            $list = explode(',',$room -> people ?? '');
            if($room -> boss == auth() -> user()->id || in_array(auth() -> user()->id , $list)) { $flag = 1;}
            if(strlen($room -> password) > 5){
                $flagPass = 1;
            }
            return response()->json([
                'code' => 200 ,
                'room' =>   [
                    'name_room' => $room -> name_room,
                    'code' => $room -> code_room,
                    'status'    => $flag,
                    'pass'      => $flagPass
                ],
                'count' => count($list)  ,

            ]);
        }else{
            return response(['code' => 300 , 'mes' => 'Không có phòng !']);
        }
    }

    public function addPeopleRoom(Request $request)
    {

        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $room = $this -> room_groups::where('code_room',$request -> code) -> first();
        if($request-> has('pass')){
            if(Hash::check($request ->pass,$room -> password)){
                $textAddPeople = $room -> people .','.auth()->user()->id ;
                $textAddPeople = ltrim($textAddPeople,",");
                $room -> people = $textAddPeople;
                $room -> content_chat = $room -> content_chat . ','. auth()->user() -> id .'--' .  auth()->user() -> name .
                    ' đã tham gia '.'--' .$dt->toDateTimeString().'--'.auth()->user() -> id;
                $room -> save();
                event(new NotifiSendRoom($room -> id));
                event(new NotificationC(auth()->user() -> id ));
                return response(['code' => 200 , 'code_room' => $request -> code , 'id' => $room -> id]);
            }else{
                return response(['code' => 300 , 'message' => 'Không đúng mã phòng ']);
            }
        }else{
            $textAddPeople = $room -> people .','.auth()->user()->id ;
            $textAddPeople = ltrim($textAddPeople,",");
            $room -> people = $textAddPeople;
            $room -> content_chat = $room -> content_chat . ','. auth()->user() -> id .'--' .  auth()->user() -> name .
                ' đã tham gia '.'--' .$dt->toDateTimeString().'--'.auth()->user() -> id;
            $room -> save();
            event(new NotifiSendRoom($room -> id));
            event(new NotificationC(auth()->user() -> id ));
            return response(['code' => 200 , 'code_room' => $request -> code, 'id' => $room -> id]);
        }
    }

    public function removeRoom(Request $request)
    {
        $room = $this -> room_groups::where('code_room',$request -> code) -> first();
        if($room-> boss == auth()->user() -> id) {
            $room->delete();
        }
        event(new SendChat(Auth::user()->id));
        event(new NotificationC(auth()->user() -> id ));
        return response(['code' => 200]);
    }
    public function viewPeopleRoom(Request $request)
    {
        $userPeople = [];
        $room = $this -> room_groups::where('code_room',$request -> code) -> first();
        $people = explode(',',$room->people);
        foreach($people as $p) {
            if($userOne = $this->user::find($p)){
                array_push($userPeople,$userOne);
            };
        };
        return response()->json(
            [
                'code' => 200 ,
                'userBoss' => $this -> user::find($room->boss),
                'userPeople' => $userPeople
            ]
        );
    }
    public function outRoom (Request $request) {

        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $room = $this -> room_groups::where('code_room',$request -> code) -> first();
        $grId = $room -> id ;
        $listPeoPleNew = '';
        if(!$request -> has('id')){
            $people = explode(',',$room->people);
            foreach ($people as $peo)
            {
                if(auth() -> user() -> id  != $peo)
                {
                    $listPeoPleNew.=$peo.',';
                }
                $listPeoPleNew = rtrim($listPeoPleNew,",");
                $textContenChat = rtrim($room -> content_chat , ",");
                $room -> people = $listPeoPleNew;
                $room -> content_chat = $textContenChat .',' .
                    auth() -> user() -> id .'--'.
                    auth() -> user() ->name.' đã rời khỏi nhóm ' .'--'.
                    $dt->toDateTimeString().'--'.auth() -> user() -> id ;
                $room -> save();
                event(new RedirectUser($request -> id ));
                event(new NotifiSendRoom($grId));
            }
        }
        if(auth() -> user() -> id == $room -> boss){
            $people = explode(',',$room->people);
            foreach ($people as $peo)
            {
                if($request -> id != $peo)
                {
                    $listPeoPleNew.=$peo.',';
                }
            }
            $listPeoPleNew = rtrim($listPeoPleNew,",");
            $textContenChat = rtrim($room -> content_chat , ",");
            $room -> people = $listPeoPleNew;
            $room -> content_chat = $textContenChat .',' .
                auth() -> user() -> id .'--'.
                $this -> user::find($request -> id)->name.' đã bị xóa khỏi nhóm ' .'--'.
                $dt->toDateTimeString().'--'.auth() -> user() -> id ;
            $room -> save();
            event(new RedirectUser($request -> id ));
            event(new NotifiSendRoom($grId));
        }

    }
}
