<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;


use Lcs13761\EredeLaravel\Contracts\StoreConfigInterface;
use Lcs13761\EredeLaravel\Enums\EndpointType;
use Lcs13761\EredeLaravel\Enums\Environment;

readonly class StoreConfigDTO implements StoreConfigInterface
{
    public function __construct(
        private string      $clientId,
        private string      $clientSecret,
        private Environment $environment,
        private int         $timeout = 30,
        private int         $bufferMinuter = 2,
        private bool        $oauthType,
        private string      $cachePrefixKey,
        private string      $cachePrefixExpiration,
    )
    {
    }

    public static function fromConfig(array $config): static
    {
        return new static(
            clientId: $config['clientId'],
            clientSecret: $config['clientSecret'],
            environment: $config['sandbox'] ? Environment::SANDBOX : Environment::PRODUCTION,
            timeout: $config['timeout'] ?? 30,
            bufferMinuter: $config['token_buffer_minutes'] ?? 2,
            oauthType: $config['oauth_type'],
            cachePrefixKey: $config['cache_prefix_key'],
            cachePrefixExpiration: $config['cache_prefix_expiration']
        );
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Retorna a URL base da API
     *
     * @param EndpointType $endpointType
     * @return string
     */
    public function getApiBaseUrl(EndpointType $endpointType): string
    {
        return data_get($this->environment->getApiUrl(), $endpointType->value);
    }

    /**
     * Retorna a URL do endpoint OAuth2
     *
     * @return string
     */
    public function getOAuthUrl(): string
    {
        return $this->environment->getOAuthUrl();
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getBufferMinuter(): int
    {
        return $this->bufferMinuter;
    }

    /**
     * @return bool
     */
    public function getOAuthType(): bool
    {
        return $this->oauthType;
    }

    public function getCachePrefixKey(): string
    {
        return $this->cachePrefixKey;
    }

    public function getCachePrefixExpiration(): string
    {
        return $this->cachePrefixExpiration;
    }
}
