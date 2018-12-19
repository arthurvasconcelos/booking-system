<?php

namespace App\Http\Controllers;

use App\MeetingRoom;

class MeetingRoomController extends Controller {
    public function index() {
        return response()->json(MeetingRoom::all()->toArray());
    }
}
