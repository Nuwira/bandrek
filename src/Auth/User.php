<?php

namespace Nuwira\Bandrek\Auth;

use Illuminate\Foundation\Auth\User as BaseUser;
use Nuwira\Bandrek\Password\CanResetPassword;

abstract class User extends BaseUser
{
    use CanResetPassword;
}
