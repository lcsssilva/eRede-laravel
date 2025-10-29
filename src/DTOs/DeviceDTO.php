<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\DTOs;

use Lcsssilva\EredeLaravel\Contracts\DTOFromArray;
use Lcsssilva\EredeLaravel\Contracts\DTOToArray;
use Lcsssilva\EredeLaravel\Traits\CreateObject;
use Lcsssilva\EredeLaravel\Traits\SerializeTrait;

readonly class DeviceDTO implements DTOToArray, DTOFromArray
{
    use CreateObject, SerializeTrait;

    public function __construct(
        public ?string $colorDepth = null,
        public ?string $deviceType3ds = null,
        public ?string $javaEnabled = null,
        public ?string $javaScriptEnabled = null,
        public ?string $language = null,
        public ?string $screenHeight = null,
        public ?string $screenWidth = null,
        public ?string $timeZoneOffset = null,
    ) {}
}