<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Lcsssilva\EredeLaravel\Contracts\EredeServiceInterface;
use Lcsssilva\EredeLaravel\Services\EredeTransaction;

/**
 * @method static EredeTransaction transaction()
 */
class Erede extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EredeServiceInterface::class;
    }
}