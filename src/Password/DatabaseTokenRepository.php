<?php

namespace Nuwira\Gembok\Password;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{
    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return (new Gembok($this->hashKey))->generateToken();
    }
}
