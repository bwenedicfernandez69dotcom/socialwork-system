<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    $this->call([
        FacultySeeder::class,
        SubjectSeeder::class,
        RoomSeeder::class,
        SectionSeeder::class,
        OfferingSeeder::class,
        ScheduleSeeder::class,
    ]);
}
}
