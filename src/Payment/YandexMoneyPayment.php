<?php

declare(strict_types=1);

namespace App\Payment;

/**
 * Метод оплаты: Яндекс.Деньги
 */
class YandexMoneyPayment extends AbstractPayment
{
    protected static $title = 'Яндекс.Деньги';

    /**
     * Constructor.
     */
    public function __construct()
    {

    }
}
