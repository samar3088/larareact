<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //protected $guarded = [];

    protected $fillable = ['name', 'email', 'mobile', 'lead_source'];
}
