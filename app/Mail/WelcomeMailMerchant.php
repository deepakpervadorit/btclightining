<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMailMerchant extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $staff;

    public function __construct($staff,$password)
    {
        $this->staff = $staff;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome_merchant')  // Your email view
                    ->from(config('mail.from.address'), config('mail.from.name'))  // Set sender address explicitly
                    ->with([
                        'name' => $this->staff->name,
                        'email' => $this->staff->email,
                        'password' => $this->password,
                    ]);
    }
}
