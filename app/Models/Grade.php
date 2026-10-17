<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'period',
        'score',
        'competency',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
