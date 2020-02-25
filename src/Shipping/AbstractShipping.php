<?php

declare(strict_types=1);

namespace App\Shipping;

use App\Entity\Address;
use App\Entity\Order;
use App\Shipping\Form\AddressFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractShipping implements ShippingInterface
{
    use ContainerAwareTrait;

    /** @var Order */
    protected $order;

    /** @var EntityManagerInterface */
    protected $em;

    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    public function getTitle(): string
    {
        if (isset(static::$title)) {
            return static::$title;
        } else {
            throw new \Exception('Нужно задать название метода доставки');
        }
    }

    public function getPrice(): int
    {
        return 0;
    }

    public function handleOrder(Order $order)
    {
        $this->order = $order;
        $order->setShippingPrice($this->getPrice());
    }

    public function handleRequest(Request $request)
    {
        $form = $this->getAddressingForm();

        if ($form) {
            $form->handleRequest($request);

            $address = $this->em->find(Address::class, (int) $form->get('address')->getData());

            if (empty($address)) {
                throw new \Exception('Не верно указан адрес');
            }

            $this->order
                ->setShippingAddress($address)
                ->setCheckoutStatus(Order::CHECKOUT_ADDRESSED)
            ;
        } else {
            throw new \Exception('Не создалась форма адресации');
        }
    }

    public function getAddressingForm(): ? FormInterface
    {
        try {
            $form = $this->createForm(AddressFormType::class, $this->order);
        } catch (\Exception $e) {
            return null;
        }

        return $form;
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @final
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @final
     */
    protected function createFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        return $this->container->get('form.factory')->createBuilder(FormType::class, $data, $options);
    }
}
