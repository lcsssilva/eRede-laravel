<?php

namespace Lcsssilva\EredeLaravel\DTOs;

use Lcsssilva\EredeLaravel\Contracts\DTOFromArray;
use Lcsssilva\EredeLaravel\Contracts\DTOToArray;
use Lcsssilva\EredeLaravel\Traits\CreateObject;
use Lcsssilva\EredeLaravel\Traits\SerializeTrait;

readonly class CryptogramDTO implements DTOToArray, DTOFromArray
{
    use SerializeTrait, CreateObject;

    public function __construct(
        public ?string $tokenCryptogram = null,
        public ?string $eci = null,
        public ?string $expirationQrCode = null)
    {
    }
}