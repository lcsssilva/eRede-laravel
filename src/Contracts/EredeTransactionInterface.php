<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\Contracts;

use Lcs13761\EredeLaravel\DTOs\TransactionDTO;

interface EredeTransactionInterface
{
    public function createTransaction(TransactionDTO $transactionData): TransactionDTO;
    public function captureTransaction(string $transactionId, int $amount = null): TransactionDTO;
    public function cancelTransaction(string $transactionId, int $amount = null): TransactionDTO;
    public function getTransaction(string $transactionId): TransactionDTO;
    public function getTransactionByReference(string $reference): TransactionDTO;

}