<?php

namespace Lcs13761\EredeLaravel\Abstracts;

use Exception;
use InvalidArgumentException;
use Lcs13761\EredeLaravel\Exception\RedeException;
use Lcs13761\EredeLaravel\Store;
use Lcs13761\EredeLaravel\Transaction;
use RuntimeException;

abstract class AbstractTransactionsService extends AbstractService
{
    protected ?Transaction $transaction;

    private string $tid;

    /**
     * AbstractTransactionsService constructor.
     *
     * @param Store $store
     * @param Transaction|null $transaction
     */
    public function __construct(Store $store, Transaction $transaction = null)
    {
        parent::__construct($store);

        $this->transaction = $transaction;
    }

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

        return $this->sendRequest($json, AbstractService::POST);
    }

    /**
     * @return string
     */
    public function getTid(): string
    {
        return $this->tid;
    }

    /**
     * @param string $tid
     * @return $this
     */
    public function setTid(string $tid): static
    {
        $this->tid = $tid;
        return $this;
    }

    /**
     * @return string
     * @see    AbstractService::getService()
     */
    protected function getService(): string
    {
        return 'transactions';
    }

    /**
     * @param string $response
     * @param int $statusCode
     *
     * @return Transaction
     * @throws RedeException
     * @throws InvalidArgumentException
     * @throws Exception
     * @see    AbstractService::parseResponse()
     */
    protected function parseResponse(string $response, int $statusCode): Transaction
    {
        $previous = null;

        if ($this->transaction === null) {
            $this->transaction = new Transaction();
        }

        try {
            $this->transaction->jsonUnserialize($response);
        } catch (InvalidArgumentException $e) {
            $previous = $e;
        }

        if ($statusCode >= 400) {
            throw new RedeException(
                $this->transaction->returnMessage ?? __('Api Error'),
                (int)$this->transaction->returnCode,
                $previous
            );
        }

        return $this->transaction;
    }
}
