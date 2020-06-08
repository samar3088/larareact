<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageDetail extends Model
{
    //protected $guarded = [];
    protected $fillable = ['package_name', 'no_of_pax', 'indoor_outdoor', 'price', 'package_include', 'city'];

    public function package()
    {
        return $this->belongsTo('App\Package', 'package_name');
    }

}
