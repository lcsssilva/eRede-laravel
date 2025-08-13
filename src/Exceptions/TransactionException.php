<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Exceptions;

class TransactionException extends EredeException
{
    public static function invalidTransaction(string $reason, array $context = []): self
    {
        return new self(
            message: "Transação inválida: {$reason}",
            context: $context
        );
    }

    public static function transactionNotFound(string $transactionId): self
    {
        return new self(
            message: "Transação não encontrada: {$transactionId}",
            context: ['transaction_id' => $transactionId]
        );
    }
}