<?php

namespace App\Http\Controllers\Api\User\TraitUser;

use App\Models\Friend;
use App\Models\Profile;
use App\Models\Room;
use App\Models\Room_Gruop;
use App\Models\User;

trait ContructUser
{
    protected $user ;
    protected $friend ;
    protected $gruop_room ;
    protected $room ;
    protected $profile ;
    public function __construct(User $user , Friend $friend ,   Room_Gruop $gruop_room , Room  $room , Profile $profile)
    {
        $this->user = $user;
        $this->friend = $friend;
        $this->gruop_room = $gruop_room;
        $this->room = $room;
        $this->profile = $profile;
    }
}
