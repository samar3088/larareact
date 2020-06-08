<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorDet extends Model
{
    //protected $guarded = [];
    protected $fillable = ['showtime', 'selvalue', 'selects', 'name', 'email', 'mobile', 'addressproof', 'chequeproof', 'panproof'];

    protected $table = 'vendor_det';
}
