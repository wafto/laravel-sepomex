<?php

namespace Aftab\Sepomex\Facades;

use Illuminate\Support\Facades\Facade;
use Aftab\Sepomex\Contracts\SepomexContract;

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
