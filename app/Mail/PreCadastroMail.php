<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PreCadastroMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_data = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
        $this->subject('SELECT | Pedido de cadastro');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->mail_data['email'])->replyTo($this->mail_data['email'])
                                                     ->view('email.email-pre-cadastro');
    }
}
