<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Services;

use Lcs13761\EredeLaravel\Contracts\HttpClientInterface;

final readonly class EredeService
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function transaction(): EredeTransaction
    {
        return new EredeTransaction($this->httpClient);
    }

}