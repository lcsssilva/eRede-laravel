<?php

namespace Lcs13761\EredeLaravel;

use Lcs13761\EredeLaravel\Traits\CreateTrait;
use DateTime;
use Exception;

class Refund
{
    use CreateTrait;

    private ?int $amount = null;

    private ?DateTime $refundDateTime = null;

    private ?string $refundId = null;

    private ?string $status = null;

    private ?string $txId = null;

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return DateTime|null
     */
    public function getRefundDateTime(): ?DateTime
    {
        return $this->refundDateTime;
    }

    /**
     * @param string $refundDateTime
     *
     * @return $this
     * @throws Exception
     */
    public function setRefundDateTime(string $refundDateTime): static
    {
        $this->refundDateTime = new DateTime($refundDateTime);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefundId(): ?string
    {
        return $this->refundId;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getTxId(): ?string
    {
        return $this->txId;
    }

    public function toArray(): array
    {
        return [
            'refundId' => $this->refundId,
            'status' => $this->status,
            'refundDateTime' => $this->refundDateTime,
            'amount' => $this->amount,
            'txId' => $this->txId
        ];
    }


}
