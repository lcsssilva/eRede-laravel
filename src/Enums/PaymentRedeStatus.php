<?php

namespace Lcsssilva\EredeLaravel\Enums;

enum PaymentRedeStatus: string
{
    case Pending = 'pending';
    case Cancelled = 'canceled';
    case Approved = 'approved';
    case Denied = 'denied';

    case Overdue = 'overdue';

    public function getLabel(): string|null
    {
        return match ($this) {
            self::Pending => __('Pending'),
            self::Overdue => __('Overdue'),
            self::Denied => __('Denied'),
            self::Cancelled => __('Cancelled'),
            self::Approved => __('Confirmed'),
        };
    }
}
