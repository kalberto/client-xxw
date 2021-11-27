<?php

namespace App\Notifications\Web;

use App\Model\Configuracao;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountResetN extends Notification
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
		$configuracao = Configuracao::first();
		if(isset($configuracao->url_inscricao) && $configuracao->url_inscricao != ''){
			$url = $configuracao->url_inscricao . (substr($configuracao->url_inscricao,-1) == '/' ? '' : '/').'nova-senha?token='.$this->token;
		}else{
			$url = 'https://staging-inscricoes.paliativo.org.br/nova-senha?token='.$this->token;
		}
		return (new MailMessage)
			->subject('xxw - Atualizamos nosso sistema')
			->line('Você está recebendo este email porque estamos atualizando nossos sistemas.')
			// ->action('Reset Password', url(config('app.url') . route('password.reset', $this->token, false)))
			->action('Reset Password', $url)
			->line('Precisamos que você troque sua senha e atualize seus dados.')
			->from('noreply@mobvox.com.br', 'etools')
			->view('email.email-troca-sistema', ['nome' => $this->nome]);
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
