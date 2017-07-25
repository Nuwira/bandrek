<?php

namespace Nuwira\Gembok;

interface GembokContract
{
    public function getRandomCode($length = 6, $toString = true);

    public function generateToken();

    public function getTokenFromCode($code);

    public function getCodeFromToken($token, $toString = true);
}
