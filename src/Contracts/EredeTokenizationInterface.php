<?php

namespace Lcsssilva\EredeLaravel\Contracts;

use Lcsssilva\EredeLaravel\DTOs\PaymentRequestDTO;

interface EredeTokenizationInterface
{
    public function createTokenization(PaymentRequestDTO $tokenizationData): PaymentRequestDTO;

    public function getTokenization(string $tokenizationId): PaymentRequestDTO;

    public function managementTokenization(string $tokenizationId): PaymentRequestDTO;

    public function getCryptogramByTokenizationId(PaymentRequestDTO $tokenizationData, $tokenizationId): PaymentRequestDTO;

}