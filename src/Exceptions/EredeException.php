<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Exceptions;

use Exception;

class EredeException extends Exception
{
    public function __construct(
        string $message,
        int $code = 0,
        private readonly ?array $context = null,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): ?array
    {
        return $this->context;
    }
}