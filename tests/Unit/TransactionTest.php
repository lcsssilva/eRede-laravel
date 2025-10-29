<?php

use Lcsssilva\EredeLaravel\DTOs\QrCodeDTO;
use Lcsssilva\EredeLaravel\DTOs\PaymentRequestDTO;


describe('TransactionDTO', function () {
    it('creates transaction dto with basic data', function () {

        $transaction = (new PaymentRequestDTO())
            ->setAmount(10000)
            ->setReference('ORDER-123')
            ->creditCard('5448280000000007', 123, 01, 28, 'João Silva');

        expect($transaction->getReference())->toBe('ORDER-123');
        expect($transaction->getKind())->toBe('credit');
        expect($transaction->getCreditCard()['cardholderName'])->toBe('João Silva');
    });
//

//
    it('creates transaction dto with qr code response', function () {
        $date = QrCodeDTO::getDateTimeExpirationForTransaction();

        $transaction = (new PaymentRequestDTO())
            ->setAmount(100.00)
            ->setReference('ORDER-123')
            ->pix()->setQrCode($date);

        expect($transaction->getQrCode())->toBeArray();
        expect($transaction->getQrCode()["dateTimeExpiration"])->toBe($date['dateTimeExpiration']);
        expect($transaction->getKind())->toBe(PaymentRequestDTO::PIX);
    });
});