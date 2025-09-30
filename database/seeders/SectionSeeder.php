<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['name' => 'BSW 1A', 'year_level' => '1st Year', 'program' => 'BS Social Work'],
            ['name' => 'BSW 2A', 'year_level' => '2nd Year', 'program' => 'BS Social Work'],
            ['name' => 'BSW 3A', 'year_level' => '3rd Year', 'program' => 'BS Social Work'],
            ['name' => 'BSW 4A', 'year_level' => '4th Year', 'program' => 'BS Social Work'],
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
