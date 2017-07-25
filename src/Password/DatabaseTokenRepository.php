<?php

namespace Nuwira\Gembok\Password;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
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
     * Determine if a token record exists and is valid.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string                                      $code
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $code)
    {
        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();

        $codeFromToken = $this->getGembok()->getCodeFromToken($record['token'], true);
        $hashedCode = $this->hasher->make($codeFromToken);

        return $record &&
               ! $this->tokenExpired($record['created_at']) &&
                 $this->hasher->check($code, $hashedCode);
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
