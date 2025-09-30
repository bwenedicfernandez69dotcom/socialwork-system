<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@scheduling.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create scheduler
        $scheduler = User::create([
            'name' => 'Scheduler User',
            'email' => 'scheduler@scheduling.test',
            'password' => bcrypt('password'),
            'role' => 'scheduler',
        ]);

        // Create faculty users + faculty records
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Faculty {$i}",
                'email' => "faculty{$i}@scheduling.test",
                'password' => bcrypt('password'),
                'role' => 'faculty',
            ]);

            Faculty::create([
                'user_id' => $user->id,
                'employee_id' => 'FAC-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'department' => 'Social Work',
                'preferences' => json_encode([
                    'preferred_days' => ['Mon', 'Wed', 'Fri'],
                    'preferred_time' => '08:00-12:00'
                ]),
            ]);
        }
    }
}
