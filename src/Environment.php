<?php

namespace Lcs13761\EredeLaravel;

class Environment
{
    public const VERSION = 'v1';

    private ?string $ip = null;

    private ?string $sessionId = null;

    private string $endpoint;

    /**
     * Creates an environment with its base url and version
     *
     */
    public function __construct()
    {
        $baseUrl = config('erede.sandbox')
            ? config('erede.sandbox_authorization')
            : config('erede.production_authorization');

        $this->endpoint = sprintf('%s/%s/', $baseUrl, Environment::VERSION);
    }

    /**
     * @param string $service
     *
     * @return string Gets the environment endpoint
     */
    public function getEndpoint(string $service): string
    {
        return $this->endpoint . $service;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return $this
     */
    public function setIp(string $ip): static
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return $this
     */
    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = $sessionId;

        return $this;
    }

}
