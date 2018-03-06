<?php

namespace Nuwira\Bandrek;

use Hashids\Hashids;

class Bandrek implements BandrekContract
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
     * (default value: 64)
     *
     * @var int
     * @access protected
     */
    protected $tokenLength = 64;

    /**
     * The alphabet for tokens
     *
     * (default value: 'qwertyuioplkjhgfdsazxcvbnm0987654321QAZXSWEDCVFRTGBNHYUJMKIOLP')
     *
     * @var string
     * @access protected
     */
    protected $alphabet = 'qwertyuioplkjhgfdsazxcvbnm0987654321QAZXSWEDCVFRTGBNHYUJMKIOLP';

    /**
     * Create Bandrek instance.
     *
     * @access public
     * @param string $salt          (default: '')
     * @param int    $minHashLength (default: 0)
     * @param mixed  $alphabet      (default: null)
     */
    public function __construct($salt = '', $minHashLength = 0, $alphabet = null)
    {
        if ($minHashLength > 0 && $minHashLength >= $this->tokenLength) {
            $this->tokenLength = $minHashLength;
        }

        if (! empty($alphabet) && strlen($alphabet) >= 26) {
            $this->alphabet = $alphabet;
        }

        $this->hashids = new Hashids($salt, $this->tokenLength, $this->alphabet);
    }

    /**
     * Get random number as code.
     *
     * @access public
     * @param  int   $length   (default: 6)
     * @param  bool  $toString (default: true)
     * @return mixed
     */
    public function getRandomCode($length = 6, $toString = true)
    {
        if ($length < 3) {
            $length = $this->codeLength;
        }

        $codes = [];

        for ($i = 0; $i < $length; $i++) {
            array_push($codes, mt_rand(0, 9));
        }

        if (! $toString) {
            return $codes;
        }

        return $this->arrayToString($codes);
    }

    /**
     * Generate token.
     *
     * @access public
     */
    public function generateToken()
    {
        $codes = $this->getRandomCode($this->codeLength, false);

        return $this->getTokenFromCode($codes);
    }

    /**
     * Get token string from code.
     *
     * @access public
     * @param  mixed  $code
     * @return string
     */
    public function getTokenFromCode($code)
    {
        if (! is_array($code)) {
            $code = str_split($code);
            $code = array_values($code);
        }

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
        $codes = array_values($codes);

        if (! $toString) {
            return $codes;
        }

        return $this->arrayToString($codes);
    }

    /**
     * Convert array codes to string code.
     *
     * @access public
     * @param  array  $array
     * @return string
     */
    public function arrayToString($array)
    {
        if (! is_array($array)) {
            return (string) $array;
        }

        return sprintf('%0'.count($array).'d', implode('', $array));
    }

    /**
     * Get hasher information.
     *
     * @param  string $token
     * @return array
     */
    public function getInfo($token)
    {
        $code = $this->getCodeFromToken($token, true);

        return [
            'algo' => 0,
            'algoName' => 'bandrek',
            'options' => [
                'code' => strlen($code),
                'token' => $this->tokenLength,
                'keys' => strlen($this->alphabet),
            ],
        ];
    }
}
