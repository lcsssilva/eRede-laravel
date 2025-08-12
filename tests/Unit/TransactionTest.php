<?php


use Lcs13761\EredeLaravel\Transaction;

describe('Transaction', function () {

    it('creates transaction instance', function () {
        $transaction = new Transaction();

        expect($transaction)->toBeTransaction();
    });

    it('can set amount', function ($amount) {
        $transaction = new Transaction();

        $result = $transaction->setAmount($amount);

        expect($result)->toBe($transaction);
    })->with([
        100,
        1000,
        5000,
        10000
    ]);

    it('can set capture mode', function ($captureMode) {
        $transaction = new Transaction();

        $result = $transaction->capture($captureMode);

        expect($result)->toBe($transaction);
        expect($transaction->capture)->toBe($captureMode);
    })->with([
        true,
        false
    ]);

    it('can serialize to json', function () {
        $transaction = createRealTransaction();

        $json = json_encode($transaction);

        expect($json)->toBeString();
        expect($json)->not->toBeFalse();
    });

    it('maintains chainable interface', function () {
        $transaction = new Transaction();

        $result = $transaction
            ->setAmount(1500)
            ->capture(true);

        expect($result)->toBe($transaction);
        expect($transaction->capture)->toBeTrue();
    });

});
