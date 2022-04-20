<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public function fuellings () {
        return $this->hasMany(Fuelling::class);
    }

    public function tripTickets () {
        return $this->hasMany(TripTicket::class);
    }

    public function meta () {
        return CarMeta::find($this->car_meta_id);
    }
}
