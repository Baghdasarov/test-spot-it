<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'points',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

    public function gameSessions()
    {
        return $this->belongsToMany(GameSession::class)->withPivot('score');
    }
}
