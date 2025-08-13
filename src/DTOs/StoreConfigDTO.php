<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

readonly class StoreConfigDTO
{
    public function __construct(
        public string $filiation,
        public string $token,
        public EnvironmentDTO $environment,
    ) {}

    public static function fromConfig(array $config): self
    {
        $environment = $config['sandbox'] ? $config['sandbox_authorization'] : $config['production_authorization'];

        return new self(
            filiation: $config['filiation'],
            token: $config['api_token'],
            environment: (new EnvironmentDTO($environment)),
        );
    }
}