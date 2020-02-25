<?php

declare(strict_types=1);

namespace App\Shipping;

use App\Entity\Order;
use App\Entity\PickUpLocation;
use App\Shipping\Form\PickupFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Самовывоз
 */
class Pickup extends AbstractShipping
{
    protected static $title = 'Самовывоз';

    /**
     * Pickup constructor.
     */
    public function __construct()
    {
    }

    public function getAddressingForm(): ? FormInterface
    {
        $form = $this->createForm(PickupFormType::class);

        return $form;
    }

    public function handleRequest(Request $request)
    {
        $form = $this->getAddressingForm();

        if ($form) {
            $form->handleRequest($request);

            $address = $this->em->find(PickUpLocation::class, (int) $form->get('pick_up_location')->getData());

            if (empty($address)) {
                throw new \Exception('Не верно указан адрес');
            }

            $this->order
                ->setShippingPickUpLocation($address)
                ->setCheckoutStatus(Order::CHECKOUT_ADDRESSED)
            ;
        } else {
            throw new \Exception('Не создалась форма адресации');
        }
    }
}
