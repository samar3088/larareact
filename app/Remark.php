<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    //protected $guarded = [];

    protected $fillable = ['quoteid', 'remarks'];
}
