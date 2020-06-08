<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = ['theme_name', 'theme_type'];

    public function subthemes()
    {
        return $this->hasMany('App\Subtheme', 'theme_id', 'id');
    }
}
