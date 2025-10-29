<?php

namespace Lcs13761\EredeLaravel\Enums;

enum Environment: string
{
    case SANDBOX = 'sandbox';
    case PRODUCTION = 'production';

    /**
     * Retorna a URL base da API
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return match ($this) {
            self::SANDBOX => config('erede.api.sandbox'),
            self::PRODUCTION => config('erede.api.production'),
        };
    }

    /**
     * Retorna a URL do endpoint OAuth2
     *
     * @return string
     */
    public function getOAuthUrl(): string
    {
        return match ($this) {
            self::SANDBOX =>  config('erede.oauth.sandbox'),
            self::PRODUCTION => config('erede.oauth.production'),
        };
    }

    /**
     * Verifica se é ambiente de sandbox
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        return $this === self::SANDBOX;
    }

    /**
     * Verifica se é ambiente de produção
     *
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this === self::PRODUCTION;
    }
}
