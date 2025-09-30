<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offering;
use App\Models\Faculty;
use App\Models\Room;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $facultyList = Faculty::all();
        $rooms = Room::all();

        // Only get offerings that are still pending
        $pendingOfferings = Offering::where('status', 'pending')->get();

        foreach ($pendingOfferings as $offering) {
            // Randomly decide if we assign this offering now
            if (rand(0, 1) === 1) {
                $faculty = $facultyList->random();
                $room = $rooms->random();

                // Random start time between 8:00 AM and 3:00 PM
                $startHour = rand(8, 15);
                $startMinute = (rand(0, 1) === 0) ? '00' : '30';
                $startTime = Carbon::createFromTime($startHour, $startMinute);

                $offering->update([
                    'faculty_id' => $faculty->id,
                    'room_id' => $room->id,
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $startTime->copy()->addHour()->format('H:i:s'),
                    'days' => $this->randomDays(),
                    'status' => 'assigned',
                ]);
            }
        }
    }

    private function randomDays()
    {
        $options = ['Mon-Wed', 'Tue-Thu', 'Mon-Wed-Fri', 'Sat'];
        return $options[array_rand($options)];
    }
}
