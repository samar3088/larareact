<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadInvoice extends Model
{
    //protected $guarded = [];
    protected $fillable = ['quoteid', 'invoice_path', 'invoice_number', 'customer_gst'];
}
