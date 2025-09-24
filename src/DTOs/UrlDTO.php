<?php

namespace Lcs13761\EredeLaravel\DTOs;

use Lcs13761\EredeLaravel\Contracts\DTOFromArray;
use Lcs13761\EredeLaravel\Contracts\DTOToArray;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

readonly class UrlDTO implements DTOToArray, DTOFromArray
{
    use SerializeTrait, CreateObject;

    public const CALLBACK = 'callback';
    public const THREE_D_SECURE_FAILURE = 'threeDSecureFailure';
    public const THREE_D_SECURE_SUCCESS = 'threeDSecureSuccess';

    public function __construct(
        public ?string $url = null,
        public ?string $kind = self::CALLBACK,
    ) {
    }

}