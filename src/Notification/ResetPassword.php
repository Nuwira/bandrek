<?php

namespace Nuwira\Bandrek\Notification;

use Config;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Nuwira\Bandrek\Bandrek;

class ResetPassword extends BaseResetPassword
{
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
