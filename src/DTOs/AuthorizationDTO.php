<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\DTOs;

use DateTimeInterface;
use Lcsssilva\EredeLaravel\Contracts\DTOFromArray;
use Lcsssilva\EredeLaravel\Contracts\DTOToArray;
use Lcsssilva\EredeLaravel\Enums\PaymentRedeStatus;
use Lcsssilva\EredeLaravel\Traits\CreateObject;
use Lcsssilva\EredeLaravel\Traits\SerializeTrait;

readonly class AuthorizationDTO implements DTOToArray, DTOFromArray
{
    use SerializeTrait, CreateObject;

    public function __construct(
        public ?string            $affiliation = null,
        public ?string            $txid = null,
        public ?int               $amount = null,
        public ?string            $authorizationCode = null,
        public ?string            $cardBin = null,
        public ?string            $cardHolderName = null,
        public ?DateTimeInterface $dateTime = null,
        public ?int               $installments = null,
        public ?string            $kind = null,
        public ?string            $last4 = null,
        public ?string            $nsu = null,
        public ?string            $origin = null,
        public ?string            $reference = null,
        public ?string            $returnCode = null,
        public ?string            $returnMessage = null,
        public ?string            $status = null,
        public ?string            $tid = null,
        public ?string            $code = null,
        public ?string            $message = null,
        public ?string            $lr = null,
        public ?string            $arp = null,
    )
    {
    }

    public function isApproved(): bool
    {
        return strtolower($this->status) == PaymentRedeStatus::Approved->value;
    }
}