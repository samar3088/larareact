<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payorder extends Model
{
    //protected $guarded = [];

    protected $fillable = ['quoteid', 'invoicedate', 'quotedate', 'razorid', 'city'];

    public function quotation()
    {
        return $this->belongsTo('App\Quotation', 'quoteid');
    }
}
