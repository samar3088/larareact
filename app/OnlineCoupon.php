<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlineCoupon extends Model
{
    protected $fillable = ['quotation', 'promocode', 'discountgiven'];

    public function quotation()
    {
        return $this->belongsTo('App\Quotation', 'quotation');
    }
}
