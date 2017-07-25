<?php

namespace Nuwira\Gembok\Auth;

use Illuminate\Foundation\Auth\User as BaseUser;
use Nuwira\Gembok\Password\CanResetPassword;

class User extends BaseUser
{
    use CanResetPassword;
}
