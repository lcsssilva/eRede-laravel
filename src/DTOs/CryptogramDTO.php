<?php

namespace Lcs13761\EredeLaravel\DTOs;

use Lcs13761\EredeLaravel\Contracts\DTOFromArray;
use Lcs13761\EredeLaravel\Contracts\DTOToArray;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

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