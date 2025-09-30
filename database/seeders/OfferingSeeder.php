<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Offering;

class OfferingSeeder extends Seeder
{
    public function run(): void
    {
        $sections = Section::all();
        $subjects = Subject::all();

        foreach ($sections as $section) {
            foreach ($subjects as $subject) {
                Offering::create([
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'semester' => '1st Semester',
                    'school_year' => '2025-2026',
                    'status' => 'pending', // ready for scheduler to assign
                ]);
            }
        }
    }
}
