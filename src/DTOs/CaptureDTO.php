<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use DateTimeInterface;
use Exception;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

readonly class CaptureDTO
{
    use SerializeTrait, CreateObject;

    public function __construct(
        public ?string            $nsu = null,
        public ?DateTimeInterface $dateTime = null,
    )
    {
    }

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