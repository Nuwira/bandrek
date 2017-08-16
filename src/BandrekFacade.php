<?php

namespace Nuwira\Bandrek;

use Illuminate\Support\Facades\Facade;

class BandrekFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bandrek';
    }
}
