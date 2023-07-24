<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;
    public $fromAddress;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $userName, $fromAddress)
    {
        // print_r($userName);
        // exit;
        $this->otp = $otp;
        $this->userName = $userName;
        $this->fromAddress = $fromAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mails.OtpMail');
    }
}
