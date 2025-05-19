<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithdrawMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $amount)
    {
        $this->user = $user;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Withdraw Successful')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.withdraw');
    }
}
