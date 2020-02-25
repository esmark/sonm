<?php

declare(strict_types=1);

namespace App\Payment;

interface PaymentMethodInterface
{
    const TYPE_CASH             = 0;
    const TYPE_CARD             = 1;
    const TYPE_ELECTRONIC       = 2;
    const TYPE_CRYPTOCURRENCY   = 3;
}
