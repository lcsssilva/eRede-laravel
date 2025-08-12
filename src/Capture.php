<?php

namespace Lcs13761\EredeLaravel;

use Lcs13761\EredeLaravel\Traits\CreateTrait;
use DateTime;

class Capture
{
    use CreateTrait;

    private ?int $amount = null;

    private ?DateTime $dateTime = null;

    private ?string $nsu = null;

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
    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    /**
     * @return string|null
     */
    public function getNsu(): ?string
    {
        return $this->nsu;
    }
}
