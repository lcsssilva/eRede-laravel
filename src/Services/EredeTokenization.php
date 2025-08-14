<?php

namespace Lcs13761\EredeLaravel\Services;

use Lcs13761\EredeLaravel\Contracts\EredeTokenizationInterface;
use Lcs13761\EredeLaravel\Contracts\HttpClientInterface;
use Lcs13761\EredeLaravel\DTOs\PaymentRequestDTO;
use Lcs13761\EredeLaravel\Enums\EndpointType;
use Lcs13761\EredeLaravel\Enums\HttpMethod;
use Lcs13761\EredeLaravel\Exceptions\TransactionException;

readonly class EredeTokenization implements EredeTokenizationInterface
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function createTokenization(PaymentRequestDTO $tokenizationData): PaymentRequestDTO
    {
        $response = $this->httpClient->request(
            method: HttpMethod::POST,
            endpoint: "",
            data: $tokenizationData->toArray(),
            endpointType: EndpointType::TOKENIZATION
        );

        if (!$response->isSuccessful()) {
            throw TransactionException::invalidTransaction('Falha ao criar tokenization', $response->data);
        }

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    public function getTokenization(string $tokenizationId): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::GET, endpoint: $tokenizationId, endpointType: EndpointType::TOKENIZATION);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    public function managementTokenization(string $tokenizationId): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::PUT, endpoint: $tokenizationId, endpointType: EndpointType::TOKENIZATION);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    public function getCryptogramByTokenizationId(PaymentRequestDTO $tokenizationData, $tokenizationId): PaymentRequestDTO
    {
        $response = $this->httpClient->request(
            method: HttpMethod::POST,
            endpoint: $tokenizationId,
            data: $tokenizationData->toArray(),
            endpointType: EndpointType::TOKENIZATION
        );

        return (new PaymentRequestDTO)->fromArray($response->data);
    }
}