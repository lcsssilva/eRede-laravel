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
    return $this->toBeInstanceOf(\Lcs13761\EredeLaravel\EredeService::class);
});

expect()->extend('toBeTransaction', function () {
    return $this->toBeInstanceOf(\Lcs13761\EredeLaravel\Transaction::class);
});


/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Criar uma Transaction real para testes
 */
function createRealTransaction(): \Lcs13761\EredeLaravel\Transaction
{
    $transaction = new \Lcs13761\EredeLaravel\Transaction();
    $transaction->setAmount(1000); // R$ 10,00
    $transaction->capture()->pix()->setQrCode();

    return $transaction;
}


/**
 * Criar Transaction com dados mínimos necessários
 */
function createMinimalTransaction(): \Lcs13761\EredeLaravel\Transaction
{
    $transaction = new \Lcs13761\EredeLaravel\Transaction();

    // Configurar propriedades mínimas necessárias
    $transaction->setAmount(1000);
    $transaction->capture(true);

    // Se a Transaction precisar de mais campos, adicione aqui
    return $transaction;
}

function createMockTransaction(): \Mockery\MockInterface
{
    $transaction = Mockery::mock(\Lcs13761\EredeLaravel\Transaction::class);
    $transaction->setAmount(1000)->capture(true);

    $transaction->shouldReceive('setAmount')->andReturnSelf();
    $transaction->shouldReceive('capture')->andReturnSelf();

    return $transaction;
}

function createEredeService(): \Lcs13761\EredeLaravel\EredeService
{
    return new \Lcs13761\EredeLaravel\EredeService('test-filiation', 'test-token');
}

function createStore(): \Lcs13761\EredeLaravel\Store
{
    return new \Lcs13761\EredeLaravel\Store('test-filiation', 'test-token');
}


