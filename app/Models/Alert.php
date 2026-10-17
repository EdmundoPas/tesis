<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'student_id',
        'tipo',
        'nivel',
        'descripcion',
        'estado',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
