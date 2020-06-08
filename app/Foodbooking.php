<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foodbooking extends Model
{
    //protected $guarded = [];

    protected $fillable = ['name', 'email', 'mobile', 'date', 'city', 'peopleinvited', 'fooditems', 'status'];
}
