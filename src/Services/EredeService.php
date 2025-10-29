<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Services;

use Lcsssilva\EredeLaravel\Contracts\EredeTokenizationInterface;
use Lcsssilva\EredeLaravel\Contracts\HttpClientInterface;
use Lcsssilva\EredeLaravel\Contracts\EredeServiceInterface;

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