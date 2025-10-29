<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Exceptions;

class HttpException extends EredeException
{
    public static function requestFailed(int $statusCode, string $response): self
    {
        return new self(
            message: "Falha na requisição HTTP",
            code: $statusCode,
            context: ['response' => $response]
        );
    }
}