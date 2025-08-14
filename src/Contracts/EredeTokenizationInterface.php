<?php

namespace Lcs13761\EredeLaravel\Contracts;

use Lcs13761\EredeLaravel\DTOs\PaymentRequestDTO;

interface EredeTokenizationInterface
{
    public function createTokenization(PaymentRequestDTO $tokenizationData): PaymentRequestDTO;

    public function getTokenization(string $tokenizationId): PaymentRequestDTO;

    public function managementTokenization(string $tokenizationId): PaymentRequestDTO;

    public function getCryptogramByTokenizationId(PaymentRequestDTO $tokenizationData, $tokenizationId): PaymentRequestDTO;

}