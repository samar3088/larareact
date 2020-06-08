<?php

namespace App\Mail;

use App\Quotation;
use App\UploadInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceAttachment extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $quotation;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UploadInvoice $invoice,Quotation $quotation,$message)
    {
        $this->invoice = $invoice;
        $this->quotation = $quotation;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Event Booking Details from Tosshead";
        $email = $this->from('support@tosshead.com','Tosshead Support')
                ->attachFromStorage($this->invoice->invoice_path,'invoice.pdf')
                ->subject($subject)
                ->view('emails.invoiceattached')
                ->with('message', $this->message);
        return $email;

        /* foreach ($this->attachment as $item) {
                $email->attach($item);
        } */
        //return $this->from('support@tosshead.com','Tosshead Support')->subject($subject)->view('emails.cusquotation')->with('message', $this->message);

    }
}
