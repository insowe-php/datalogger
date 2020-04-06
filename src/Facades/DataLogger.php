<?php

namespace Insowe\DataLogger\Facades;

use Illuminate\Support\Facades\Facade;

class DataLogger extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'datalogger';
    }
}
