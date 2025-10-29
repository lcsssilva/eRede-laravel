<?php

use Lcsssilva\EredeLaravel\DTOs\PaymentRequestDTO;
use Lcsssilva\EredeLaravel\Services\EredeTransaction;
use Lcsssilva\EredeLaravel\Contracts\HttpClientInterface;
use Lcsssilva\EredeLaravel\DTOs\ResponseDTO;

// ✅ DTO correto
use Lcsssilva\EredeLaravel\Exceptions\TransactionException;
use Lcsssilva\EredeLaravel\Enums\HttpMethod;

beforeEach(function () {
    $this->mockHttpClient = Mockery::mock(HttpClientInterface::class);
    $this->eredeService = new EredeTransaction($this->mockHttpClient);
});

afterEach(function () {
    Mockery::close();
});

describe('EredeTransaction Service', function () {
    it('creates transaction successfully', function () {
        // Arrange
        $transactionRequest = createTransactionDTO();
        $responseData = createSuccessfulTransactionResponse();

        $this->mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->andReturn(new ResponseDTO( // ✅ DTO correto
                statusCode: 200,
                data: $responseData,
                success: true
            ));

        // Act
        $result = $this->eredeService->createTransaction($transactionRequest);

        // Assert
        expect($result)->toBeInstanceOf(PaymentRequestDTO::class);
        expect($result->getTid())->toBe('TID123456');
        expect($result->getAmount())->toBe(10000);
    });

    it('handles transaction creation failure', function () {
        // Arrange
        $transactionRequest = createTransactionDTO();

        $this->mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->andReturn(new ResponseDTO(
                statusCode: 400,
                data: ['error' => 'Invalid transaction'],
                success: false, // ✅ Propriedade correta
                error: 'Invalid transaction'
            ));

        // Act & Assert
        expect(fn() => $this->eredeService->createTransaction($transactionRequest))
            ->toThrow(TransactionException::class, 'Falha ao criar transação');
    });

    it('captures transaction successfully', function () {
        // Arrange
        $transactionId = 'TID123456';
        $amount = 5000;
        $responseData = createCapturedTransactionResponse();

        $this->mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->with(
                HttpMethod::PUT, // ✅ Sem namespace completo
                $transactionId,
                ['amount' => $amount]
            )
            ->andReturn(new ResponseDTO(
                statusCode: 200,
                data: $responseData,
                success: true
            ));

        // Act
        $result = $this->eredeService->captureTransaction($transactionId, $amount);

        // Assert
        expect($result)->toBeInstanceOf(PaymentRequestDTO::class);
        expect($result->getTid())->toBe($transactionId);
    });

    it('cancels transaction successfully', function () {
        // Arrange
        $transactionId = 'TID123456';
        $responseData = createCancelledTransactionResponse();

        $this->mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->with(
                HttpMethod::DELETE,
                $transactionId,
                []
            )
            ->andReturn(new ResponseDTO(
                statusCode: 200,
                data: $responseData,
                success: true
            ));

        // Act
        $result = $this->eredeService->cancelTransaction($transactionId);

        // Assert
        expect($result)->toBeInstanceOf(PaymentRequestDTO::class);
        expect($result->getTid())->toBe($transactionId);
    });

    it('gets transaction by id successfully', function () {
        // Arrange
        $transactionId = 'TID123456';
        $responseData = createSuccessfulTransactionResponse();

        $this->mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->with(
                HttpMethod::GET,
                $transactionId
            )
            ->andReturn(new ResponseDTO(
                statusCode: 200,
                data: $responseData,
                success: true
            ));

        // Act
        $result = $this->eredeService->getTransaction($transactionId);

        // Assert
        expect($result)->toBeInstanceOf(PaymentRequestDTO::class);
        expect($result->getTid())->toBe($transactionId);
    });

    it('gets transaction by reference successfully', function () {
        // Arrange
        $reference = 'ORDER-123';
        $responseData = createSuccessfulTransactionResponse();

        $this->mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->with(
                HttpMethod::GET,
                '',
                ['reference' => $reference]
            )
            ->andReturn(new ResponseDTO(
                statusCode: 200,
                data: $responseData,
                success: true
            ));

        // Act
        $result = $this->eredeService->getTransactionByReference($reference);

        // Assert
        expect($result)->toBeInstanceOf(PaymentRequestDTO::class);
        expect($result->getReference())->toBe($reference);
    });

    it('handles transaction not found', function () {
        // Arrange
        $transactionId = 'INVALID_TID';

        $this->mockHttpClient
            ->shouldReceive('request')
            ->once()
            ->andReturn(new ResponseDTO(
                statusCode: 404,
                data: ['error' => 'Transaction not found'],
                success: false,
                error: 'Transaction not found'
            ));

        // Act & Assert
        expect(fn() => $this->eredeService->getTransaction($transactionId))
            ->toThrow(TransactionException::class, 'Transação não encontrada');
    });
});