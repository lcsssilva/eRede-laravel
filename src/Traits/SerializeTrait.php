<?php

namespace Lcsssilva\EredeLaravel\Traits;

trait SerializeTrait
{
    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });
    }
}
