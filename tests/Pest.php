<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Lcs13761\EredeLaravel\Tests\TestCase;
use Lcs13761\EredeLaravel\DTOs\PaymentRequestDTO;
use Lcs13761\EredeLaravel\DTOs\QrCodeDTO;

uses(TestCase::class)->in('Feature');
uses(TestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeEredeService', function () {
    return $this->toBeInstanceOf(\Lcs13761\EredeLaravel\Services\EredeTransaction::class);
});

expect()->extend('toBeTransactionDTO', function () {
    return $this->toBeInstanceOf(PaymentRequestDTO::class);
});

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Criar TransactionDTO para testes
 */
function createTransactionDTO(): PaymentRequestDTO
{
    return  (new PaymentRequestDTO)->setAmount(10000)->setReference('ORDER-' . uniqid())
        ->creditCard('4111111111111111', 123,12,25, 'João Silva');
}

/**
 * Criar PIX TransactionDTO para testes
 */
function createPixTransactionDTO(): PaymentRequestDTO
{
    return  (new PaymentRequestDTO)->setAmount(10000)->setReference('PIX-ORDER-' . uniqid())->pix()->setQrCode(QrCodeDTO::getDateTimeExpirationForTransaction());
}

/**
 * Criar resposta de transação bem-sucedida
 */
function createSuccessfulTransactionResponse(): array
{
    return [
        'tid' => 'TID123456',
        'nsu' => 'NSU789',
        'amount' => 10000,
        'reference' => 'ORDER-123',
        'kind' => 'credit',
        'status' => 'approved',
        'authorizationCode' => 'AUTH123',
        'returnCode' => '00',
        'returnMessage' => 'Success',
        'dateTime' => '2024-01-15T10:30:00',
        'authorization' => [
            'code' => 'AUTH123',
            'message' => 'Approved',
            'dateTime' => '2024-01-15T10:30:00',
            'nsu' => 'NSU789'
        ],
        'brand' => [
            'name' => 'Visa',
            'returnCode' => '00',
            'returnMessage' => 'Success'
        ]
    ];
}

/**
 * Criar resposta de transação capturada
 */
function createCapturedTransactionResponse(): array
{
    return [
        'tid' => 'TID123456',
        'amount' => 10000,
        'status' => 'captured',
        'capture' => [
            'nsu' => 'NSU789',
            'dateTime' => '2024-01-15T10:45:00'
        ]
    ];
}

/**
 * Criar resposta de transação cancelada
 */
function createCancelledTransactionResponse(): array
{
    return [
        'tid' => 'TID123456',
        'amount' => 10000,
        'status' => 'canceled',
        'returnCode' => '00',
        'returnMessage' => 'Transaction cancelled successfully'
    ];
}

/**
 * Criar mock do HttpClient
 */
function createMockHttpClient(): \Mockery\MockInterface
{
    return Mockery::mock(\Lcs13761\EredeLaravel\Contracts\HttpClientInterface::class);
}