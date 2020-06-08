<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BdayModalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Custom Birthday Requested frm User";
        $email = $this->from($this->data['email'],$this->data['name'])
                ->attachFromStorage($this->data['file'])
                ->subject($subject)
                ->view('emails.homemodal')
                ->with('data', $this->data);
        return $email;
    }
}
