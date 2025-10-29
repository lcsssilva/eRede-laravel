<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Contracts;

use Lcsssilva\EredeLaravel\Enums\EndpointType;
use Lcsssilva\EredeLaravel\Enums\Environment;

interface StoreConfigInterface
{

    public function getOAuthType(): bool;
    /**
     * Retorna o Client ID para OAuth2
     *
     * @return string
     */
    public function getClientId(): string;

    /**
     * Retorna o Client Secret para OAuth2
     *
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * Retorna o ambiente (sandbox ou production)
     *
     * @return Environment
     */
    public function getEnvironment(): Environment;

    /**
     * Retorna a URL base da API
     *
     * @param EndpointType $endpointType
     * @return string
     */
    public function getApiBaseUrl(EndpointType $endpointType): string;

    /**
     * Retorna a URL do endpoint OAuth2
     *
     * @return string
     */
    public function getOAuthUrl(): string;

    /**
     * Retorna o timeout das requisições
     *
     * @return int
     */
    public function getTimeout(): int;

    /**
     * @return mixed
     */
    public function getBufferMinuter(): mixed;

    /**
     * @return string
     */
    public function getCachePrefixKey(): string;

    /**
     * @return string
     */
    public function getCachePrefixExpiration(): string;
}
