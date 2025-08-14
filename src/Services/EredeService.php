<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Services;

use Lcs13761\EredeLaravel\Contracts\EredeTokenizationInterface;
use Lcs13761\EredeLaravel\Contracts\HttpClientInterface;
use Lcs13761\EredeLaravel\Contracts\EredeServiceInterface;

final readonly class EredeService implements EredeServiceInterface
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /**
     * @return EredeTransaction
     */
    public function transaction(): EredeTransaction
    {
        return new EredeTransaction($this->httpClient);
    }

    public function tokenization(): EredeTokenizationInterface
    {
        return new EredeTokenization($this->httpClient);
    }
}