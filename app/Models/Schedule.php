<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model 
{ 
    use HasFactory; 

    protected $fillable = [
        'faculty_id', 
        'subject_id', 
        'room_id', 
        'section_id',
	'year_level', 
        'days',            
        'start_time', 
        'end_time', 
        'delivery_mode',   
        'semester',        
        'school_year',     
        'status',          // track assignment status
        'faculty_response' // track accept/decline
    ]; 

    // Relationships
    public function faculty() 
    { 
        return $this->belongsTo(Faculty::class); 
    } 

    public function subject() 
    { 
        return $this->belongsTo(Subject::class); 
    } 

    public function room() 
    { 
        return $this->belongsTo(Room::class); 
    } 

    public function section() 
    { 
        return $this->belongsTo(Section::class); 
    } 
} 
