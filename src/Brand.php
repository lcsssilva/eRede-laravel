<?php

namespace Lcs13761\EredeLaravel;

use Lcs13761\EredeLaravel\Traits\CreateTrait;

class Brand
{
    use CreateTrait;

    private ?string $name = null;

    private ?string $returnCode = null;

    private ?string $brandTid = null;

    private ?string $authorizationCode = null;

    private ?string $returnMessage = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    /**
     * @return string|null
     */
    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'brandTid' => $this->brandTid,
            'authorizationCode' => $this->authorizationCode,
        ];
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }
}
