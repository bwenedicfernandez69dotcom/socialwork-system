<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'SW101', 'title' => 'Introduction to Social Work', 'units' => 3, 'delivery_mode' => 'face-to-face'],
            ['code' => 'SW102', 'title' => 'Human Behavior and Social Environment', 'units' => 3, 'delivery_mode' => 'face-to-face'],
            ['code' => 'SW103', 'title' => 'Social Welfare Policies', 'units' => 3, 'delivery_mode' => 'online'],
            ['code' => 'SW104', 'title' => 'Field Instruction 1', 'units' => 6, 'delivery_mode' => 'modular'],
            ['code' => 'SW105', 'title' => 'Community Development', 'units' => 3, 'delivery_mode' => 'face-to-face'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
