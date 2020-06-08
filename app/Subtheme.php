<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtheme extends Model
{
    protected $fillable = ['theme_id', 'type', 'sub_theme_name', 'actual_price', 'discounted_price', 'label', 'particular', 'rating', 'views', 'file', 'description', 'what_included', 'need_to_know'];

    public function theme()
    {
        return $this->belongsTo('App\Theme');
    }

    public function subthemeimages()
    {
        return $this->hasMany('App\Subthemeimages', 'sub_theme_id');
    }

}
