<?php

use Lcs13761\EredeLaravel\DTOs\QrCodeDTO;
use Lcs13761\EredeLaravel\DTOs\TransactionDTO;
use Lcs13761\EredeLaravel\DTOs\AuthorizationDTO;
use Lcs13761\EredeLaravel\DTOs\BrandDTO;
use Lcs13761\EredeLaravel\DTOs\ThreeDSecureDTO;
use Lcs13761\EredeLaravel\DTOs\DeviceDTO;


describe('TransactionDTO', function () {
    it('creates transaction dto with basic data', function () {
        $data = [
            'tid' => 'TID123456',
            'amount' => 10000,
            'reference' => 'ORDER-123',
            'kind' => 'credit',
            'cardHolderName' => 'João Silva',
        ];

        $transaction = TransactionDTO::create($data);

        expect($transaction->tid)->toBe('TID123456');
        expect($transaction->amount)->toBe(10000);
        expect($transaction->reference)->toBe('ORDER-123');
        expect($transaction->kind)->toBe('credit');
        expect($transaction->cardHolderName)->toBe('João Silva');
    });
//
    it('creates transaction dto with nested authorization and brand', function () {
        $data = [
            'tid' => 'TID123456',
            'amount' => 10000,
            'authorization' => [
                'code' => 'AUTH123',
                'message' => 'Approved',
                'dateTime' => '2024-01-15T10:30:00',
                'brand' => [
                    'name' => 'Visa',
                    'returnCode' => '00',
                    'returnMessage' => 'Success'
                ]
            ]
        ];

        $transaction = TransactionDTO::create($data);

        expect($transaction->authorization)->toBeInstanceOf(AuthorizationDTO::class);
        expect($transaction->authorization->code)->toBe('AUTH123');
        expect($transaction->authorization->dateTime)->toBeInstanceOf(DateTime::class);
        expect($transaction->brand)->toBeInstanceOf(BrandDTO::class);
        expect($transaction->brand->name)->toBe('Visa');
    });

    it('creates transaction dto with qr code response', function () {
        $date = QrCodeDTO::getDateTimeExpirationForTransaction();

        $data = [
            'tid' => 'TID123456',
            'amount' => 10000,
            'kind' => TransactionDTO::PIX,
            'qrCode' => $date
        ];

        $transaction = TransactionDTO::create($data);

        expect($transaction->qrCode)->toBeArray();
        expect($transaction->qrCode["dateTimeExpiration"])->toBe($date['dateTimeExpiration']);
        expect($transaction->tid)->toBe( 'TID123456');
        expect($transaction->kind)->toBe(TransactionDTO::PIX);
    });
//
    it('creates transaction dto with threeDSecure and device', function () {
        $data = [
            'tid' => 'TID123456',
            'threeDSecure' => [
                'onFailure' => 'decline',
                'embedded' => true,
                'threeDIndicator' => 2,
                'Device' => [
                    'colorDepth' => '24',
                    'language' => 'pt-BR',
                    'screenHeight' => '1080'
                ]
            ]
        ];

        $transaction = TransactionDTO::create($data);

        expect($transaction->threeDSecure)->toBeInstanceOf(ThreeDSecureDTO::class);
        expect($transaction->threeDSecure->onFailure)->toBe('decline');
        expect($transaction->threeDSecure->Device)->toBeInstanceOf(DeviceDTO::class);
        expect($transaction->threeDSecure->Device->language)->toBe('pt-BR');
    });

    it('serializes to json correctly', function () {
        $transaction = TransactionDTO::create([
            'tid' => 'TID123456',
            'amount' => 10000,
            'reference' => 'ORDER-123',
            'kind' => 'credit',
            'capture' => true,
            'cardHolderName' => 'João Silva'
        ]);

        $json = json_encode($transaction);

        $decoded = json_decode($json, true);

        expect($decoded['tid'])->toBe('TID123456');
        expect($decoded['amount'])->toBe(10000);
        expect($decoded['capture'])->toBe('true'); // boolean vira string
        expect($decoded['kind'])->toBe('credit');
    });

    it('handles convenience methods', function () {
        $transaction = TransactionDTO::create([
            'kind' => 'credit',
            'authorizationCode' => 'AUTH123'
        ]);

        expect($transaction->isCredit())->toBeTrue();
        expect($transaction->isPix())->toBeFalse();
        expect($transaction->isApproved())->toBeTrue();
    });

    it('handles empty qr code correctly', function () {
        $transaction = TransactionDTO::create([
            'tid' => 'TID123456',
            'qrCode' => []
        ]);

        expect($transaction->hasQrCode())->toBeFalse();
        expect($transaction->getQrCode())->toBeEmpty();
    });
});