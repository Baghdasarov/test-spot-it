<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'winner_id',
        'game_state'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function currentTurnPlayer()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }
}
