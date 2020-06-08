<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subthemeimages extends Model
{
    protected $fillable = ['sub_theme_id', 'path'];

    public function subtheme()
    {
        return $this->belongsTo('App\Subtheme', 'sub_theme_id');
    }

}
