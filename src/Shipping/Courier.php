<?php

declare(strict_types=1);

namespace App\Shipping;

use App\Entity\Address;
use App\Shipping\Form\AddressFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;

/**
 * Курьером
 */
class Courier extends AbstractShipping
{
    protected static $title = 'Курьером';

    /**
     * Constructor.
     */
    public function __construct()
    {

    }

    public function getPrice(): int
    {
        return 300;
    }
}
