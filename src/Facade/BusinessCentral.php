<?php

namespace Ktr\BusinessCentral\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Ktr\BusinessCentral\BusinessCentral
 */
class BusinessCentral extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'business-central-sdk';
    }
}
