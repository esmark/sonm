<?php

declare(strict_types=1);

namespace App\Payment;

/**
 * Метод оплаты: наличные
 */
class CashPayment extends AbstractPayment
{
    protected static $title = 'Наличные';

    /**
     * Constructor.
     */
    public function __construct()
    {

    }
}
