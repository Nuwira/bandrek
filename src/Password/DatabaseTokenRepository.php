<?php

namespace Nuwira\Gembok\Password;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;
use Nuwira\Gembok\Gembok;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{
    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return $this->getGembok()->generateToken();
    }

    /**
     * Get Gembok instance.
     *
     * @access protected
     */
    protected function getGembok()
    {
        return new Gembok($this->hashKey);
    }
}
