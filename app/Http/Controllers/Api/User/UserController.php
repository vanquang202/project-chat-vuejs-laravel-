<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\User\TraitUser\ContructUser;
use App\Http\Controllers\Api\User\TraitUser\FriendUser;
use App\Http\Controllers\Api\User\TraitUser\ProfileUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
   use ContructUser;

    public function index()
    {
        $data = [];
        $dataGruop = [];
        $id = Auth::user() -> id ;
        $friend = $this->friend::where('user_id',$id )-> first();
        $textList = ltrim( $friend -> friend ?? '' , ",");
        $listFriend = explode(',',$textList);

        // Gruop
        $gruops = $this-> gruop_room::all();
        foreach ($gruops as $gruop)
        {
            $gruopData = [];
            $flagView = 0;
            $listBoss = explode(',',$gruop->boss ?? '');
            $listPeople = explode(',',$gruop->people ?? '');
            $listPContentChat = explode(',',$gruop->content_chat ?? '');

            if(in_array($id , $listBoss) || in_array($id,$listPeople)){
                $endList = explode('--' ,end($listPContentChat) ) ;
                if(count($endList) > 2){
                    $listView = explode('-',$endList[3]) ;
                    if(in_array(auth()->user()->id , $listView)) $flagView = 1;
                    $gruopData = [
                        'name_room' => $gruop->name_room,
                        'id' => $gruop->id,
                        'code_room' => $gruop->code_room,
                        'endCOntentChatName' => $this -> user::find($endList[0])->name,
                        'contentChatEnd' => $endList[1],
                        'statusChat' => $flagView
                    ];
                    array_push($dataGruop , $gruopData);
                }

            }
        }
         if( $listFriend[0] != ""){
             foreach ($listFriend as $fi)
             {
                 $arrSaveUser = [];
                 $fg = 1;
                 $id_room = ( $fi) * $id;
                 $room = $this -> room::where('room_user', $id_room)->first();
                 $data_chat = explode(',', $room -> data ?? '');
                 $endListChat =explode( '--', end($data_chat));
                 if(count($endListChat) > 2) if($endListChat[1] && $endListChat[0] != $id && $endListChat[3] == 0) $fg = 0;

                 $userS = $this->user ::find($fi);
                 $arrSaveUser = [
                     'image' => $userS -> image,
                     'name' => $userS -> name,
                     'id' => $userS -> id ,
                     'flagSeen' =>  $fg,
                 ];
                 array_push($data ,$arrSaveUser);
             }
         }

        return response()->json([
            'code'       => 200,
            'data'       => $data,
            'room_gruop' => $dataGruop,
        ]);
    }

   use ProfileUser;

    use FriendUser;


}
