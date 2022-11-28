<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'start_time' => 'array',
        'end_time' => 'array',
        'course_id' => 'array',
        'year_level' => 'array',
    ];

    public function sheets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttendanceSheet::class, 'event_id', 'id');
    }
}
