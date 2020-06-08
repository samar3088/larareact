<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subdetailsvendor extends Model
{
    //protected $guarded = [];
    protected $fillable = ['vendortitle', 'include', 'maincategory', 'price'];

    public function maincategory()
    {
        return $this->belongsTo('App\Maincategory', 'maincategory');
    }
}
