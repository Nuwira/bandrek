<?php

namespace Nuwira\Bandrek\Notification;

use Config;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Nuwira\Bandrek\Bandrek;

abstract class BandrekNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The password reset code.
     *
     * @var string
     */
    public $code;

    /**
     * Create a notification instance.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->code = $this->parseTokenToCode($token);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed                                          $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action($this->code, url(config('app.url').route('password.reset', $this->token, false)))
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Parse token to code.
     *
     * @access protected
     * @param  string $token
     * @return string
     */
    protected function parseTokenToCode($token)
    {
        $key = substr(Config::get('app.key'), 7);

        return (new Bandrek($key))->getCodeFromToken($token);
    }
}
