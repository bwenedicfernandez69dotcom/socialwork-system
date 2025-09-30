<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'faculty_id', 'type', 'message', 'is_read', 'status', 'schedule_id'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
