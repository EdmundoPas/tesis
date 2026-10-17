<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BullyingReport extends Model
{
    protected $table = 'bullying_reports';

    protected $fillable = [
        'reporter_id',
        'student_id',
        'tipo',
        'descripcion',
        'anonimo',
        'estado',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
