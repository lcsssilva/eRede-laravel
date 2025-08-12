<?php

namespace Lcs13761\EredeLaravel\Services;

use Lcs13761\EredeLaravel\Abstracts\AbstractTransactionsService;
use RuntimeException;

class CancelTransactionService extends AbstractTransactionsService
{
    /**
     * @return string
     */
    protected function getService(): string
    {
        if ($this->transaction === null) {
            throw new RuntimeException(__('Transaction was not defined yet'));
        }

        return sprintf('%s/%s/refunds', parent::getService(), $this->transaction->tid);
    }
}
