<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\Contracts;

use Lcsssilva\EredeLaravel\DTOs\PaymentRequestDTO;

interface EredeTransactionInterface
{
    public function createTransaction(PaymentRequestDTO $transactionData): PaymentRequestDTO;
    public function captureTransaction(string $transactionId, int $amount = null): PaymentRequestDTO;
    public function cancelTransaction(string $transactionId, array $data): PaymentRequestDTO;
    public function getTransaction(string $transactionId): PaymentRequestDTO;
    public function getTransactionByReference(string $reference): PaymentRequestDTO;

    public function refundTid(string $transactionId): PaymentRequestDTO;

    public function refundId(string $transactionId, string $refundId): PaymentRequestDTO;

}