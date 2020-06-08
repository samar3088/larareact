<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //protected $guarded = [];

    protected $fillable = ['couponcode','discount','min_amount','trans_type'];
}
