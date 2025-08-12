<?php

namespace Lcs13761\EredeLaravel;

use Lcs13761\EredeLaravel\Interfaces\RedeSerializable;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

class Url implements RedeSerializable
{
    use SerializeTrait;

    public const CALLBACK = 'callback';
    public const THREE_D_SECURE_FAILURE = 'threeDSecureFailure';
    public const THREE_D_SECURE_SUCCESS = 'threeDSecureSuccess';

    public function __construct(private readonly string $url, private readonly string $kind = Url::CALLBACK)
    {
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }
}
