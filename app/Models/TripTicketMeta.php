<?php

namespace App\Models;

class TripTicketMeta extends \Jenssegers\Mongodb\Eloquent\Model
{

    protected $table = 'trip_tickets_meta';
    protected $connection = 'mongodb';
    protected $fillable = [
        'approved_actions'
    ];
}
