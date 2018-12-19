<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingRoom extends Model {
    use SoftDeletes;

    protected $fillable = ['name'];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
