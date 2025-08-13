<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

readonly class ResponseDTO
{
    public function __construct(public int $statusCode, public array $data, public bool $success, public ?string $error = null)
    {
    }

    public function isSuccessful(): bool
    {
        return $this->success && $this->statusCode >= 200 && $this->statusCode < 300;
    }
}