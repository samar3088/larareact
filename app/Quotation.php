<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function payorders()
    {
        return $this->hasMany('App\Payorder');
    }

    public function onlinecoupon()
    {
        return $this->hasOne('App\OnlineCoupon');
    }

    public function onlineinquiry()
    {
        return $this->hasOne('App\OnlineInquiry');
    }

}
