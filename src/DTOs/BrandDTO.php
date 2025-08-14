<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use Lcs13761\EredeLaravel\Contracts\DTOFromArray;
use Lcs13761\EredeLaravel\Contracts\DTOToArray;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

readonly class BrandDTO implements DTOToArray, DTOFromArray
{
    use CreateObject, SerializeTrait;

    public function __construct(
        public ?string $name = null,
        public ?string $returnCode = null,
        public ?string $returnMessage = null,
        public ?string $brandTid = null,
        public ?string $authorizationCode = null,
        public ?string $acquirer = null,
    ) {}
}