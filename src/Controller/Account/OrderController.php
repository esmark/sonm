<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\PaymentMethod;
use App\Entity\ShippingMethod;
use App\Entity\User;
use App\Shipping\AbstractShipping;
use App\Shipping\ShippingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/order")
 */
class OrderController extends AbstractController
{
    /**
     * @todo pagination
     *
     * @Route("/", name="account_order")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('account/order/index.html.twig', [
            'orders' => $em->getRepository(Order::class)->findBy(
                ['user' => $this->getUser()],
                ['created_at' => 'DESC']
            ),
        ]);
    }

    /**
     * @Route("/{id}/", name="account_order_edit")
     */
    public function edit(Order $order, Request $request, EntityManagerInterface $em, ContainerInterface $container): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getId() !== $order->getUser()->getId()) {
            return $this->redirectToRoute('account_order');
        }

        if ($request->query->has('choose_another_shipping_method')) {
            $order
                ->setShippingMethod(null)
                ->setShippingAddress(null)
                ->setShippingPickUpLocation(null)
                ->setShippingPrice(0)
                ->setCheckoutStatus(Order::CHECKOUT_CART)
            ;

            $em->flush();

            return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);
        }

        if ($request->isMethod('POST')) {
            if ($order->getStatus() != Order::STATUS_CART) {
                $this->addFlash('error', 'Заказ уже оформлен');

                return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);
            }

            switch ($order->getCheckoutStatus()) {
                // Первый шаг оформления заказа: выбор метода доставки
                case Order::CHECKOUT_CART:
                    $sm = $em->find(ShippingMethod::class, (int) $request->request->get('sm', 0));

                    if (empty($sm)) {
                        $this->addFlash('error', 'Не верно указан метод оплаты');

                        return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);
                    }

                    $order
                        ->setShippingMethod($sm)
                        ->setCheckoutStatus(Order::CHECKOUT_SHIPPING_SELECTED)
                    ;

                    $em->flush();

                    return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);

                // Второй шаг оформления заказа: выбор места выдачи/получения (адресация)
                case Order::CHECKOUT_SHIPPING_SELECTED:
                    /** @var AbstractShipping $shipping */
                    $shipping = $container->get($order->getShippingMethod()->getService());
                    $shipping->handleOrder($order);
                    $shipping->handleRequest($request);

                    $em->flush();

                    return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);

                // Третий шаг оформления заказа: оплата
                case Order::CHECKOUT_ADDRESSED:
                    $pm = $em->find(PaymentMethod::class, (int) $request->request->get('pm', 0));

                    if (empty($pm)) {
                        $this->addFlash('error', 'Не верно указан метод оплаты');

                        return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);
                    }

                    $payment = new Payment();
                    $payment
                        ->setAmount($order->getAmount() + $order->getShippingPrice())
                        ->setOrder($order)
                        ->setMethod($pm)
                        ->setUser($user)
                    ;

                    $order
                        ->setStatus(Order::STATUS_NEW)
                        ->setShippingStatus(Order::SHIPPING_READY)
                        ->setPaymentStatus(Order::PAYMENT_AWAITING_PAYMENT)
                        ->setCheckoutCompletedAt(new \DateTime())
                    ;

                    // @todo временная заглушка! все НЕ наличные методы - сразу считаются оплаченными.
                    if ($pm->getClass() != 'App\Payment\CashPayment') {
                        $order->setPaymentStatus(Order::PAYMENT_PAID);
                        $payment->setStatus(Payment::STATUS_PAID);
                    }

                    $em->persist($payment);
                    $em->flush();

                    $this->addFlash('success', 'Заказ оформлен');

                    return $this->redirectToRoute('account_order');
                default:

            }
        }

        $addresingForm = null;

        if ($order->getCheckoutStatus() == Order::CHECKOUT_SHIPPING_SELECTED) {
            /** @var AbstractShipping $shipping */
            $shipping = $container->get($order->getShippingMethod()->getService());
            $shipping->handleOrder($order);

            $addresingForm = $shipping->getAddressingForm();
        }

        return $this->render('account/order/edit.html.twig', [
            'addresing_form'   => $addresingForm ? $addresingForm->createView() : null,
            'order'            => $order,
            'payment_methods'  => $em->getRepository(PaymentMethod::class)->findBy(['is_enabled' => true]),
            'shipping_methods' => $em->getRepository(ShippingMethod::class)->findBy(['is_enabled' => true]),
        ]);
    }
}
