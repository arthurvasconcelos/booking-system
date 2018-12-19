<?php

use App\MeetingRoom;
use Illuminate\Database\Seeder;

class MeetingRoomsTableSeeder extends Seeder {
    public function run() {
        $rooms = ['Tatooine', 'Goldenrod', 'Gotham', 'Duckburg'];

        foreach ($rooms as $room) {
            MeetingRoom::create(['name' => $room]);
        }
    }
}
