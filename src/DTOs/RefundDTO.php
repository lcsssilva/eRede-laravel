<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\DTOs;

use DateTimeInterface;
use Lcsssilva\EredeLaravel\Contracts\DTOFromArray;
use Lcsssilva\EredeLaravel\Contracts\DTOToArray;
use Lcsssilva\EredeLaravel\Traits\CreateObject;
use Lcsssilva\EredeLaravel\Traits\SerializeTrait;

readonly class RefundDTO implements DTOToArray, DTOFromArray
{
    use SerializeTrait, CreateObject;

    public function __construct(
        public ?int               $amount = null,
        public ?DateTimeInterface $refundDateTime = null,
        public ?string            $refundId = null,
        public ?string            $status = null,
        public ?string            $txId = null
    )
    {
    }

}