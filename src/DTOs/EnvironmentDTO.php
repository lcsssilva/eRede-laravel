<?php

namespace Lcsssilva\EredeLaravel\DTOs;

readonly class EnvironmentDTO
{
    public function __construct(private ?string $baseUrl = null)
    {
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }
}