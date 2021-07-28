<?php

namespace Phobrv\BrvRecruitment\Facades;

use Illuminate\Support\Facades\Facade;

class BrvRecruitment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'brvrecruitment';
    }
}
