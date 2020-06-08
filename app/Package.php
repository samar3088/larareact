<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    //protected $guarded = [];

    protected $fillable = ['package_name', 'package_image', 'city', 'description'];

    public function packagedetails()
    {
        return $this->hasMany('App\PackageDetail', 'package_name', 'id');
    }
    
    public function city()
    {
        return $this->belongsTo('App\City', 'city');
    }

}
