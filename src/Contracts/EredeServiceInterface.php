<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Contracts;


interface EredeServiceInterface
{
    public function transaction(): EredeTransactionInterface;

}