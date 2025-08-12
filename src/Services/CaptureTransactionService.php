<?php

namespace Lcs13761\EredeLaravel\Services;

use InvalidArgumentException;
use Lcs13761\EredeLaravel\Abstracts\AbstractService;
use Lcs13761\EredeLaravel\Abstracts\AbstractTransactionsService;
use Lcs13761\EredeLaravel\Exception\RedeException;
use Lcs13761\EredeLaravel\Transaction;
use RuntimeException;

class CaptureTransactionService extends AbstractTransactionsService
{
    /**
     * @return Transaction
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws RedeException
     */
    public function execute(): Transaction
    {
        $json = json_encode($this->transaction);

        if (!is_string($json)) {
            throw new RuntimeException(__('Problem converting the Transaction object to json'));
        }

        return $this->sendRequest($json, AbstractService::PUT);
    }

    /**
     * @return string
     */
    protected function getService(): string
    {
        if ($this->transaction === null) {
            throw new RuntimeException(__('Transaction was not defined yet'));
        }

        return sprintf('%s/%s', parent::getService(), $this->transaction->tid);
    }
}
