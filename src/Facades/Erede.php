<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use Lcs13761\EredeLaravel\Contracts\EredeServiceInterface;
use Lcs13761\EredeLaravel\Services\EredeTransaction;

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