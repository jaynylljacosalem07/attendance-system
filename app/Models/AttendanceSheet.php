<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSheet extends Model
{
    use HasFactory;
    protected $guarded= [];
    protected $casts = [
        'in' => 'array',
        'out' => 'array',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    { 
        return $this->belongsTo(User::class,'id_number','id_number');
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    { 
        return $this->belongsTo(Course::class);
    }

    
}
