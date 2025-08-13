<?php

use Lcs13761\EredeLaravel\DTOs\QrCodeDTO;
use Lcs13761\EredeLaravel\DTOs\TransactionDTO;


describe('TransactionDTO', function () {
    it('creates transaction dto with basic data', function () {

        $transaction = (new TransactionDTO())
            ->setAmount(10000)
            ->setReference('ORDER-123')
            ->creditCard('5448280000000007', 123, 01, 28, 'João Silva');

        expect($transaction->reference)->toBe('ORDER-123');
        expect($transaction->kind)->toBe('credit');
        expect($transaction->creditCard['cardholderName'])->toBe('João Silva');
    });
//

//
    it('creates transaction dto with qr code response', function () {
        $date = QrCodeDTO::getDateTimeExpirationForTransaction();

        $transaction = (new TransactionDTO())
            ->setAmount(100.00)
            ->setReference('ORDER-123')
            ->pix()->setQrCode($date);



        dd($transaction);

        expect($transaction->qrCode)->toBeArray();
        expect($transaction->qrCode["dateTimeExpiration"])->toBe($date['dateTimeExpiration']);
        expect($transaction->kind)->toBe(TransactionDTO::PIX);
    });
});