<?php

declare(strict_types=1);

namespace App\Payment;

abstract class AbstractPayment implements PaymentInterface
{
    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    public function getTitle(): ?string
    {
        if (isset(static::$title)) {
            return static::$title;
        }

        return null;
    }
}
