<?php

namespace Nuwira\Gembok;

use Carbon\Carbon;
use Hashids\Hashids;

class Gembok
{
    /**
     * The length of random code generated
     *
     * (default value: 6)
     *
     * @var int
     * @access protected
     */
    protected $codeLength = 6;

    /**
     * The length of token string generated
     *
     * (default value: 40)
     *
     * @var int
     * @access protected
     */
    protected $tokenLength = 40;

    /**
     * Create Gembok instance.
     *
     * @access public
     * @param string $salt          (default: '')
     * @param int    $minHashLength (default: 0)
     * @param string $alphabet      (default: 'qwertyuioplkjhgfdsazxcvbnm0987654321QAZXSWEDCVFRTGBNHYUJMKIOLP')
     */
    public function __construct($salt = '', $minHashLength = 0, $alphabet = 'qwertyuioplkjhgfdsazxcvbnm0987654321QAZXSWEDCVFRTGBNHYUJMKIOLP')
    {
        if ($minHashLength < $this->tokenLength) {
            $minHashLength = $this->tokenLength;
        }

        $this->hashids = new Hashids($salt, $minHashLength, $alphabet);
    }
    
    /**
     * Generate array contains of random number.
     *
     * @access public
     * @param  int   $length (default: 6)
     * @return array
     */
    public function randomNumberArray($length = 6)
    {
        if ($length < 3) {
            $length = $this->codeLength;
        }

        $results = [];

        for ($i = 0; $i < $length; $i++) {
            array_push($results, mt_rand(0, 9));
        }

        return $results;
    }

    /**
     * Generate token string.
     *
     * @access public
     * @param int $length (default: 6)
     */
    public function generateToken($length = 6)
    {
        $code = $this->randomNumberArray($length);
        array_push($code, Carbon::now()->getTimestamp());

        return $this->hashids->encode($code);
    }

    /**
     * Get code from token string.
     *
     * @access public
     * @param  string $token
     * @param  bool   $toString (default: true)
     * @return mixed
     */
    public function getCodeFromToken($token, $toString = true)
    {
        $codes = $this->hashids->decode($token);
        array_pop($codes);

        if ($toString) {
            return $codes;
        }

        return sprintf('%0'.count($codes).'d', implode('', $codes));
    }

    
}
