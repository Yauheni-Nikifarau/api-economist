<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuelling extends Model
{
    use HasFactory;

    public function car () {
        return $this->belongsTo(Car::class);
    }

    public function driver () {
        return $this->belongsTo(Driver::class);
    }
}
