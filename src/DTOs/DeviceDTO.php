<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use Exception;
use JsonSerializable;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

readonly class DeviceDTO implements JsonSerializable
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

    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): self
    {
        return self::create($data);
    }
}