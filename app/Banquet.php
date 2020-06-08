<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banquet extends Model
{
    //protected $guarded = [];

    protected $fillable = ['name', 'email', 'mobile', 'date', 'city', 'instruction', 'status'];
}
