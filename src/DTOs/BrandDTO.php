<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\DTOs;

use Lcsssilva\EredeLaravel\Contracts\DTOFromArray;
use Lcsssilva\EredeLaravel\Contracts\DTOToArray;
use Lcsssilva\EredeLaravel\Traits\CreateObject;
use Lcsssilva\EredeLaravel\Traits\SerializeTrait;

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