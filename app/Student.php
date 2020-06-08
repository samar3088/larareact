<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //protected $guarded = [];

    protected $fillable = ['first_name', 'last_name'];
}
