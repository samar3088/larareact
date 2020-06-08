<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorSelection extends Model
{
    //protected $guarded = [];
    protected $fillable = ['displaytime', 'selvalue', 'selects', 'name'];
}
