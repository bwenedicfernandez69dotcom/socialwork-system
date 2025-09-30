<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['room_no' => 'R101', 'capacity' => 40, 'type' => 'Lecture'],
            ['room_no' => 'R102', 'capacity' => 35, 'type' => 'Lecture'],
            ['room_no' => 'R103', 'capacity' => 50, 'type' => 'Laboratory'],
            ['room_no' => 'R104', 'capacity' => 45, 'type' => 'Lecture'],
            ['room_no' => 'R105', 'capacity' => 30, 'type' => 'Lecture'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
