<?php

namespace Nuwira\Bandrek;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class Hasher implements HasherContract
{
    public function __construct(Bandrek $bandrek)
    {
        $this->bandrek = $bandrek;
    }

    /**
     * Hash the given value.
     *
     * @param  string $value
     * @param  array  $options
     * @return string
     */
    public function make($value, array $options = [])
    {
        return $this->bandrek->getTokenFromCode($value);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string $value
     * @param  string $hashedValue
     * @param  array  $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $this->bandrek->getCodeFromToken($value)
            == $this->bandrek->getCodeFromToken($hashedValue);
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param  string $hashedValue
     * @param  array  $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return $this->bandrek->getTokenFromCode($value);
    }
}
