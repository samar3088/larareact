<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homeslider extends Model
{
     //protected $guarded = [];

     protected $fillable = ['heading', 'subheading', 'buttontext', 'buttonlink', 'subtext', 'file'];
}
