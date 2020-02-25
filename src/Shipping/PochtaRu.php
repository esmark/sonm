<?php

declare(strict_types=1);

namespace App\Shipping;

use App\Entity\Address;
use App\Shipping\Form\AddressFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;

/**
 * Почта России
 * https://www.pochta.ru/
 */
class PochtaRu extends AbstractShipping
{
    protected static $title = 'Почта России';

    /**
     * PochtaRu constructor.
     */
    public function __construct()
    {

    }

    public function getPrice(): int
    {
        return 100;
    }
}
