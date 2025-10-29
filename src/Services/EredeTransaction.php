<?php

namespace Lcsssilva\EredeLaravel\Services;

use Exception;
use Lcsssilva\EredeLaravel\Contracts\EredeTransactionInterface;
use Lcsssilva\EredeLaravel\Contracts\HttpClientInterface;
use Lcsssilva\EredeLaravel\DTOs\PaymentRequestDTO;
use Lcsssilva\EredeLaravel\Enums\HttpMethod;
use Lcsssilva\EredeLaravel\Exceptions\TransactionException;

readonly class EredeTransaction implements EredeTransactionInterface
{

    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function createTransaction(PaymentRequestDTO $transactionData): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::POST, endpoint: "", data: $transactionData->toArray());

        if (!$response->isSuccessful()) {
            throw TransactionException::invalidTransaction('Falha ao criar transação', $response->data);
        }

        $data = $response->data;

        $data['kind'] = $transactionData->getKind();

        return (new PaymentRequestDTO)->fromArray($data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function captureTransaction(string $transactionId, ?int $amount = null): PaymentRequestDTO
    {
        $data = [];

        if ($amount !== null) $data['amount'] = $amount;

        $response = $this->httpClient->request(method: HttpMethod::PUT, endpoint: $transactionId, data: $data);

        if (!$response->isSuccessful()) throw TransactionException::invalidTransaction('Falha ao capturar transação', $response->data);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function getTransaction(string $transactionId): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::GET, endpoint: $transactionId);

        if (!$response->isSuccessful()) throw TransactionException::transactionNotFound($transactionId);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function getTransactionByReference(string $reference): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::GET, endpoint: '', data: ['reference' => $reference]);

        if (!$response->isSuccessful()) throw TransactionException::transactionNotFound($reference);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    /**
     * @param string $transactionId
     * @return PaymentRequestDTO
     * @throws TransactionException
     */
    public function refundTid(string $transactionId): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::GET, endpoint: $transactionId . "/refunds");

        if (!$response->isSuccessful()) throw TransactionException::transactionNotFound($transactionId);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    /**
     * @param string $transactionId
     * @param string $refundId
     * @return PaymentRequestDTO
     * @throws TransactionException
     */
    public function refundId(string $transactionId, string $refundId): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::GET, endpoint: $transactionId . "/refunds/" . $refundId);

        if (!$response->isSuccessful()) throw TransactionException::transactionNotFound($transactionId);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }

    /**
     * @throws TransactionException
     * @throws Exception
     */
    public function cancelTransaction(string $transactionId, array $data = []): PaymentRequestDTO
    {
        $response = $this->httpClient->request(method: HttpMethod::POST, endpoint: $transactionId . "/refunds", data: $data);

        if (!$response->isSuccessful()) throw TransactionException::invalidTransaction('Falha ao cancelar transação', $response->data);

        return (new PaymentRequestDTO)->fromArray($response->data);
    }
}