<?php

use Lcs13761\EredeLaravel\EredeService;
use Lcs13761\EredeLaravel\Services\CreateTransactionService;

beforeEach(function () {
    $this->eredeService = createEredeService();
});

afterEach(function () {
    Mockery::close();
});

describe('EredeService', function () {
    it('creates erede service with correct parameters', function () {
        $service = new EredeService('filiation', 'token');

        expect($service)->toBeEredeService();
    });

    // Testes que não fazem chamadas HTTP reais
    describe('without HTTP calls', function () {
        it('creates service instances correctly', function () {
            $transaction = createRealTransaction();

            // Testar se o service é instanciado corretamente
            $reflection = new ReflectionClass($this->eredeService);
            $method = $reflection->getMethod('create');

            // Verificar se não há erros na criação do serviço
            expect(function() use ($transaction) {
                new CreateTransactionService(createStore(), $transaction);
            })->not->toThrow(Exception::class);
        });

        it('authorizes transaction using create method', function () {
            $transaction = createRealTransaction();

            // Mock do método create para evitar HTTP call
            $mock = Mockery::mock(EredeService::class)->makePartial();
            $mock->shouldReceive('create')->once()->andReturn($transaction);

            $result = $mock->authorize($transaction);

            expect($result)->toBeTransaction();
        });

        it('handles zero amount transaction logic', function () {
            $transaction = createRealTransaction();
            $originalAmount = $transaction->amount;

            // Mock para evitar HTTP calls
            $mock = Mockery::mock(EredeService::class)->makePartial();
            $mock->shouldReceive('create')->once()->andReturn($transaction);

            $result = $mock->zero($transaction);

            expect($result)->toBeTransaction();
        });

    });
});