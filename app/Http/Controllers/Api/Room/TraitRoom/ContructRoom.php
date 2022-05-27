<?php

namespace App\Http\Controllers\Api\Room\TraitRoom;

use App\Models\Friend;
use App\Models\Room;
use App\Models\Room_Gruop;
use App\Models\User;

trait ContructRoom
{
    protected $room_groups ;
    protected $user ;
    protected $room ;
    protected $friend;
    public function __construct(Room_Gruop $room_groups,User $user , Room $room , Friend $friend)
    {
        $this->room_groups = $room_groups;
        $this->user = $user;
        $this->room = $room;
        $this->friend = $friend;
    }
}
