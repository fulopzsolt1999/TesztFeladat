<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $connection = 'mysql_carservice';
    protected $fillable = ['id', 'name', 'card_number'];
}
