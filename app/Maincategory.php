<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maincategory extends Model
{
    //protected $guarded = [];

    protected $fillable = ['cat_name'];

    public function subdetailsvendor()
    {
        return $this->hasMany('App\Subdetailsvendor');
    }
}
