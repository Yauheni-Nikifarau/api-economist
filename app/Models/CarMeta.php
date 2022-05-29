<?php

namespace App\Models;

class CarMeta extends \Jenssegers\Mongodb\Eloquent\Model
{

    protected $table = 'cars_meta';
    protected $connection = 'mongodb';
    protected $fillable = [
        'plates',
        'limits'
    ];
}
