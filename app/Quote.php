<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    //protected $guarded = [];

    protected $fillable = ['heading', 'description', 'page_type'];
}
