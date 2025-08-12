<?php

namespace Lcs13761\EredeLaravel;

class Store
{
    private Environment $environment;

    /**
     * Creates a store.
     *
     */
    public function __construct(private readonly string $filiation, private readonly string $token)
    {
        $this->environment = new Environment();
    }


    /**
     * @return string
     */
    public function getFiliation(): string
    {
        return $this->filiation;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    public function setEnvironment(Environment $environment): Store
    {
        $this->environment = $environment;

        return $this;
    }

}
