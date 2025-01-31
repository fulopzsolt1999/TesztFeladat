<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $connection = 'mysql_carservice';
    protected $fillable = ['client_id', 'car_id', 'log_number', 'event', 'event_time', 'document_id'];
}
