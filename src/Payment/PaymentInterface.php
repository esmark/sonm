<?php

declare(strict_types=1);

namespace App\Payment;

interface PaymentInterface
{
    const STATUS_AUTHORIZED = 'authorized';
    const STATUS_NEW        = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_FAILED     = 'failed';
    const STATUS_CANCELLED  = 'cancelled';
    const STATUS_REFUNDED   = 'refunded';
    const STATUS_UNKNOWN    = 'unknown';

    public function getTitle(): ?string;
}
