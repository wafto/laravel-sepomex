<?php

namespace Aftab\Sepomex\Facades;

use Aftab\Sepomex\Contracts\SepomexContract;
use Illuminate\Support\Facades\Facade;

/**
 * Class Sepomex.
 */
class Sepomex extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SepomexContract::class;
    }
}
