<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Address;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\PaymentMethod;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="account_order")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('account/order/index.html.twig', [
            'orders' => $em->getRepository(Order::class)->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/{id}/", name="account_order_edit")
     */
    public function edit(Order $order, Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getId() !== $order->getUser()->getId()) {
            return $this->redirectToRoute('account_order');
        }

        if ($request->isMethod('POST')) {
            if ($order->getStatus() != Order::STATUS_CART) {
                $this->addFlash('error', 'Заказ уже оформлен');

                return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);
            }

            $address = $em->find(Address::class, (int) $request->request->get('address', 0));
            $pm = $em->find(PaymentMethod::class, (int) $request->request->get('pm', 0));

            if (empty($pm)) {
                $this->addFlash('error', 'Не верно указан метод оплаты');

                return $this->redirectToRoute('account_order_edit', ['id' => $order->getId()]);
            }

            $payment = new Payment();
            $payment
                ->setAmount($order->getAmount())
                ->setOrder($order)
                ->setPaymentMethod($pm)
                ->setUser($user)
            ;

            $order
                ->setStatus(Order::STATUS_NEW)
                ->setShippingStatus(Order::SHIPPING_READY)
                ->setPaymentStatus(Order::PAYMENT_AWAITING_PAYMENT)
                ->setCheckoutCompletedAt(new \DateTime())
                ->setShippingAddress($address)
            ;

            $em->persist($payment);
            $em->flush();

            $this->addFlash('success', 'Заказ оформлен');

            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order/edit.html.twig', [
            'order' => $order,
            'addresses' => $em->getRepository(Address::class)->findBy(['user' => $user]),
            'payment_methods' => $em->getRepository(PaymentMethod::class)->findBy(['is_enabled' => true]),
        ]);
    }
}
