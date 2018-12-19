<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['start_at', 'end_at', 'user_id', 'room_id'];

    function user() {
        return $this->belongsTo(User::class);
    }

    function room() {
        return $this->belongsTo(MeetingRoom::class);
    }
}
