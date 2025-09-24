<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

readonly class StoreConfigDTO
{
    public function __construct(
        public string $filiation,
        public string $token,
        public EnvironmentDTO $authorizationEnvironment,
        public EnvironmentDTO $tokenizationEnvironment,
    ) {}

    public static function fromConfig(array $config): static
    {
        $authorizationUrl = $config['sandbox'] ? $config['sandbox_authorization'] : $config['production_authorization'];
        $tokenizationUrl = $config['sandbox'] ? $config['sandbox_tokenization'] : $config['production_tokenization'];


        return new static(
            filiation: $config['filiation'],
            token: $config['api_token'],
            authorizationEnvironment: new EnvironmentDTO($authorizationUrl),
            tokenizationEnvironment: new EnvironmentDTO($tokenizationUrl),
        );
    }
}