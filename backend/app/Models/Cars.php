<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    protected $connection = 'mysql_carservice';
    protected $fillable = ['client_id', 'car_id', 'type', 'registered', 'ownbrand', 'accidents'];
}
