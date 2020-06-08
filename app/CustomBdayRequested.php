<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomBdayRequested extends Model
{
    protected $table = 'custom_bday_requested';

    protected $fillable = [
        'name', 'email','mobile', 'file'
    ];
}
