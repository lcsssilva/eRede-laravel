<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use Exception;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

readonly class BrandDTO
{
    use CreateObject, SerializeTrait;

    public function __construct(
        public ?string $name = null,
        public ?string $returnCode = null,
        public ?string $returnMessage = null,
        public ?string $id = null,
        public ?string $acquirer = null,
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