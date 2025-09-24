<?php

namespace Lcs13761\EredeLaravel\Contracts;

interface DTOFromArray
{
    public static function fromArray(object|array $data): static;
}