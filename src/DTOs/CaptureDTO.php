<?php

declare(strict_types=1);

namespace Lcsssilva\EredeLaravel\DTOs;

use DateTimeInterface;
use Lcsssilva\EredeLaravel\Contracts\DTOFromArray;
use Lcsssilva\EredeLaravel\Contracts\DTOToArray;
use Lcsssilva\EredeLaravel\Traits\CreateObject;
use Lcsssilva\EredeLaravel\Traits\SerializeTrait;

readonly class CaptureDTO implements DTOToArray, DTOFromArray
{
    use SerializeTrait, CreateObject;

    public function __construct(public ?string $nsu = null, public ?DateTimeInterface $dateTime = null)
    {
    }
}