<?php

namespace Lcsssilva\EredeLaravel\Contracts;

interface DTOFromArray
{
    public static function fromArray(object|array $data): static;
}