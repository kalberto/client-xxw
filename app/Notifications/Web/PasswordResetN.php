<?php

namespace App\Notifications\Web;

use App\Model\Configuracao;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetN extends Notification
{
	/**
	 * The password reset token.
	 *
	 * @var string
	 */
	public $token;
	public $nome;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($token, $nome)
	{
		$this->token = $token;
		$this->nome = $nome;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		if(env('APP_URL') != ''){
			$url = env('APP_URL') . (substr(env('APP_URL'),-1) == '/' ? '' : '/').'nova-senha?token='.$this->token;
		}else{
			$url = 'https://stage-xxw.etools.com.br/nova-senha?token='.$this->token;
		}
		return (new MailMessage)
			->subject('xxw - Nova senha')
			->line('Você está recebendo este email pois recebemos um pedido de troca de senha')
			// ->action('Reset Password', url(config('app.url') . route('password.reset', $this->token, false)))
			->action('Reset Password', $url)
			->line('Se você não requisitou o reset, nenhuma ação é requisitada.')
			->from('falecomselect@xxw.com', 'xxw')
			->view('email.email-lost-pass', ['nome' => $this->nome]);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			//
		];
	}
}
