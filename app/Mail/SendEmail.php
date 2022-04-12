<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $magicCode;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($magicCode)
    {
        $this->magicCode = $magicCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('test email from Hovo')->view('email.check');
    }
}
