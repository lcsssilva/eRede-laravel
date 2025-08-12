<?php

namespace Lcs13761\EredeLaravel;

use Lcs13761\EredeLaravel\Enums\PaymentRedeStatus;
use Lcs13761\EredeLaravel\Traits\CreateTrait;
use DateTime;

class Authorization
{
    use CreateTrait;

    private ?string $affiliation = null;

    private ?string $txid = null;

    private ?int $amount = null;

    private ?string $authorizationCode = null;

    private ?string $cardBin = null;

    private ?string $cardHolderName = null;

    private ?DateTime $dateTime = null;

    private ?int $installments = null;

    private ?string $kind = null;

    private ?string $last4 = null;

    private string|null $nsu = null;

    private ?string $origin = null;

    private ?string $reference = null;

    private ?string $returnCode = null;

    private ?string $returnMessage = null;

    private ?string $status = null;

    private ?string $tid = null;

    /**
     * @return string|null
     */
    public function getAffiliation(): ?string
    {
        return $this->affiliation;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    /**
     * @return string|null
     */
    public function getCardBin(): ?string
    {
        return $this->cardBin;
    }

    /**
     * @return string|null
     */
    public function getCardHolderName(): ?string
    {
        return $this->cardHolderName;
    }

    /**
     * @return DateTime|null
     */
    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return int|null
     */
    public function getInstallments(): ?int
    {
        return $this->installments;
    }

    /**
     * @return string|null
     */
    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * @return string|null
     */
    public function getLast4(): ?string
    {
        return $this->last4;
    }

    /**
     * @return string|null
     */
    public function getNsu(): ?string
    {
        return $this->nsu;
    }

    /**
     * @return string|null
     */
    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @return string|null
     */
    public function getReturnCode(): ?string
    {
        return $this->returnCode;
    }

    /**
     * @return string|null
     */
    public function getReturnMessage(): ?string
    {
        return $this->returnMessage;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getTid(): ?string
    {
        return $this->tid;
    }

    public function getTxid(): ?string
    {
        return $this->txid;
    }

    public function isApproved(): bool
    {
        return strtolower($this->status) == PaymentRedeStatus::Approved->value;
    }
}
