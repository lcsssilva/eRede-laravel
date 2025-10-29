<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Contracts;


interface EredeServiceInterface
{
    public function transaction(): EredeTransactionInterface;

    public function tokenization(): EredeTokenizationInterface;
}