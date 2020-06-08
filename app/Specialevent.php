<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specialevent extends Model
{
    //protected $guarded = [];

    protected $fillable = ['name', 'email', 'mobile', 'description', 'event_date'];
}
