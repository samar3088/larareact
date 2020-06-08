<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlineInquiry extends Model
{
    protected $fillable = ['created_users', 'quotation', 'discount', 'amount'];

    public function quotation()
    {
        return $this->belongsTo('App\Quotation', 'quotation');
    }
}
