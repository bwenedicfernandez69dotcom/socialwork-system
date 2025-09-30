<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_no', 'capacity']; // adjust columns to match your DB

    /**
     * Get all schedules assigned to this room.
     */
    public function schedules()
    {
        return $this->hasMany(\App\Models\Schedule::class, 'room_id', 'id');
    }
}
