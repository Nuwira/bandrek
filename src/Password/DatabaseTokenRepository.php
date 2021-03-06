<?php

namespace Nuwira\Bandrek\Password;

use Carbon\Carbon;
use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Nuwira\Bandrek\BandrekContract;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{
    /**
     * Bandrek holder
     *
     * @var \Nuwira\Bandrek\BandrekContract
     * @access protected
     */
    protected $bandrek;

    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return $this->getBandrek()->generateToken();
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string                                      $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();

        return ! empty($record) &&
               ! $this->tokenExpired($record['created_at']) &&
                 $this->hasher->check($token, $record['token']);
    }

    /**
     * Set Bandrek instance.
     *
     * @access public
     * @param \Nuwira\Bandrek\BandrekContract $bandrek
     */
    public function setBandrek(BandrekContract $bandrek)
    {
        $this->bandrek = $bandrek;

        return $this;
    }

    /**
     * Get Bandrek instance.
     *
     * @access public
     * @return \Nuwira\Bandrek\BandrekContract
     */
    public function getBandrek()
    {
        return $this->bandrek;
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string $email
     * @param  string $token
     * @return array
     */
    protected function getPayload($email, $token)
    {
        return ['email' => $email, 'token' => $token, 'created_at' => new Carbon()];
    }
}
