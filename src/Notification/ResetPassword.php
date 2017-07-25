<?php

namespace Nuwira\Gembok\Notification;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Nuwira\Gembok\Gembok;

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
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
        $this->code = (new Gembok())->getCodeFromToken($token, true);
    }
}