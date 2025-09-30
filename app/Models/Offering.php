<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offering extends Model
{
    protected $fillable = [
        'subject_id',
        'faculty_id',
        'section_id',
        'room_id',
        'start_time',
        'end_time',
        'days',
	'delivery_mode',
        'status',
        'semester',
        'school_year',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
