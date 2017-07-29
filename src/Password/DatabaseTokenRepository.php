<?php

namespace Nuwira\Bandrek\Password;

use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;
use Nuwira\Bandrek\BandrekContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

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
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();
        
        if (preg_match('/[0-9]{6}/', $token)) {
            $token = $this->getBandrek()->getTokenFromCode($token);
        }

        return $record &&
               ! $this->tokenExpired($record['created_at']) &&
                 $this->hasher->check($token, $record['token']);
    }

    /**
     * Set Bandrek instance.
     * 
     * @access public
     * @param \Nuwira\Bandrek\BandrekContract $bandrek
     * @return void
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
}
