<?php

namespace Lcsssilva\EredeLaravel\DTOs;

use Lcsssilva\EredeLaravel\Contracts\DTOFromArray;
use Lcsssilva\EredeLaravel\Contracts\DTOToArray;
use Lcsssilva\EredeLaravel\Traits\CreateObject;
use Lcsssilva\EredeLaravel\Traits\SerializeTrait;

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