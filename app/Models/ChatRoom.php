<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'name',
        'tipo',
        'grado_seccion',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }
}
