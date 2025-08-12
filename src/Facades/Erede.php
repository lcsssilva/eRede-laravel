<?php


namespace Lcs13761\EredeLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Lcs13761\EredeLaravel\EredeService;

class Erede extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return EredeService::class;
    }
}
