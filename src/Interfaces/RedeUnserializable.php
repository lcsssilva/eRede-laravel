<?php

namespace Lcs13761\EredeLaravel\Interfaces;

interface RedeUnserializable
{
    /**
     * @param string $serialized
     *
     * @return $this
     */
    public function jsonUnserialize(string $serialized): static;
}
