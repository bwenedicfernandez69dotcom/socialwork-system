<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'day',
        'start_time',
        'end_time',
        'semester',
        'school_year',
        'status',
    ];

    /**
     * Each availability belongs to a faculty.
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
