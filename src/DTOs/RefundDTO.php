<?php

declare(strict_types=1);

namespace Lcs13761\EredeLaravel\DTOs;

use DateTimeInterface;
use Lcs13761\EredeLaravel\Contracts\DTOFromArray;
use Lcs13761\EredeLaravel\Contracts\DTOToArray;
use Lcs13761\EredeLaravel\Traits\CreateObject;
use Lcs13761\EredeLaravel\Traits\SerializeTrait;

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