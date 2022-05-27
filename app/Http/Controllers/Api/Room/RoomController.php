<?php

namespace App\Http\Controllers\Api\Room;

use App\Events\NotificationC;
use App\Events\NotifiSendRoom;
use App\Events\SendChat;
use App\Http\Controllers\Api\Room\TraitRoom\ChatRoom;
use App\Http\Controllers\Api\Room\TraitRoom\ChatRoomGruop;
use App\Http\Controllers\Api\Room\TraitRoom\ContructRoom;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    use ContructRoom;

    use ChatRoom;

    use ChatRoomGruop;


    public function dowload_file()
    {
        if (request()->has('name-file')) {
            if (!file_exists(public_path('files') . '\\' . request('name-file'))) return abort(404);
            $headers = ['Content-Type' => 'application/zip',];
            return  response()->download(public_path('files') . '/' . request('name-file'), request('name-file'), $headers);
        }
    }

    public function file_send_chat(Request $request)
    {
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $validator = Validator::make(
            request()->all(),
            [
                'file_send' => 'required|max:10000|mimes:zip'
            ],
            [
                'file_send.required' => 'Không bỏ trống ',
                'file_send.max' => 'Dung lượng file không quá 1000kb',
                'file_send.mimes' => 'Không đúng định dạng file : zip ',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->messages(),
            ]);
        };
        $nameFile = uniqid() . time() . '_' . request()->file_send->getClientOriginalName();
        $data = str_replace('-', ' ', $nameFile);
        $data = str_replace(',', ' ', $data);
        request()->file_send->move(public_path('files'), $data);
        if (request()->has('id')) {
            $id_fr = request()->id;
            $room_user = $id_fr * auth()->user()->id;

            $room = $this->room::where('room_user', $room_user)->first() ?? $this->room::create(['room_user' => $room_user]);
            $textOld = $room->data;
            $testNew = $textOld . ',' . auth()->user()->id . '--' . $data . '--' . $dt->toDateTimeString() . '--0';
            $room->data = $testNew;
            $room->save();

            event(new SendChat($id_fr));
            event(new NotificationC($id_fr));

            return;
        } elseif (request()->has('code')) {

            $room_gr = $this->room_groups::where('code_room', request('code'))->first();
            $grId = $room_gr->id;

            $textOld = rtrim($room_gr->content_chat);
            $textNew =  $textOld . ',' . auth()->user()->id  . '--' . $data . '--' . $dt->toDateTimeString() . '--' . auth()->user()->id;
            $room_gr->content_chat = $textNew;
            $room_gr->save();

            event(new NotifiSendRoom($grId));
            event(new NotificationC($grId));
            return;
        }
    }
}