<?php

namespace Lcs13761\EredeLaravel\Services;

use Exception;
use Lcs13761\EredeLaravel\Contracts\EredeServiceInterface;
use Lcs13761\EredeLaravel\Contracts\HttpClientInterface;
use Lcs13761\EredeLaravel\DTOs\TransactionDTO;
use Lcs13761\EredeLaravel\Enums\HttpMethod;
use Lcs13761\EredeLaravel\Exceptions\TransactionException;

readonly class EredeTransaction implements EredeServiceInterface
{

    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function createTransaction(TransactionDTO $transactionData): TransactionDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::POST, endpoint: 'transactions', data: $transactionData->toArray());

        if (!$response->isSuccessful()) {
            throw TransactionException::invalidTransaction('Falha ao criar transação', $response->data);
        }

        return TransactionDTO::fromArray($response->data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function captureTransaction(string $transactionId, int $amount = null): TransactionDTO
    {
        $data = [];

        if ($amount !== null) $data['amount'] = $amount;

        $response = $this->httpClient->request(method: HttpMethod::PUT, endpoint: "transactions/$transactionId", data: $data);

        if (!$response->isSuccessful()) throw TransactionException::invalidTransaction('Falha ao capturar transação', $response->data);

        return TransactionDTO::fromArray($response->data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function cancelTransaction(string $transactionId, int $amount = null): TransactionDTO
    {
        $data = [];
        if ($amount !== null) {
            $data['amount'] = $amount;
        }

        $response = $this->httpClient->request(method: HttpMethod::DELETE, endpoint: "transactions/$transactionId", data: $data);

        if (!$response->isSuccessful()) throw TransactionException::invalidTransaction('Falha ao cancelar transação', $response->data);

        return TransactionDTO::fromArray($response->data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function getTransaction(string $transactionId): TransactionDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::GET, endpoint: "transactions/$transactionId");

        if (!$response->isSuccessful()) throw TransactionException::transactionNotFound($transactionId);

        return TransactionDTO::fromArray($response->data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function getTransactionByReference(string $reference): TransactionDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::GET, endpoint: 'transactions', data: ['reference' => $reference]);

        if (!$response->isSuccessful()) throw TransactionException::transactionNotFound($reference);

        return TransactionDTO::fromArray($response->data);
    }
}