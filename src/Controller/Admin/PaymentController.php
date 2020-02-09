<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @todo pagination
     *
     * @Route("/", name="admin_payment")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/payment/index.html.twig', [
            'payments' => $em->getRepository(Payment::class)->findBy([], ['created_at' => 'DESC']),
        ]);
    }

    /**
     * @todo secure
     *
     * @Route("/{id}/approve/", name="admin_payment_approve")
     */
    public function approve(Payment $payment, EntityManagerInterface $em): RedirectResponse
    {
        $order = $payment->getOrder();

        if ($payment->getAmount() == $order->getAmount()) {
            $order->setPaymentStatus(Order::PAYMENT_PAID);
        } else {
            $order->setPaymentStatus(Order::PAYMENT_PARTIALLY_PAID);
        }

        $payment->setStatus(Payment::STATUS_PAID);

        $em->flush();

        $this->addFlash('success', 'Платёж подтверждён');

        return $this->redirectToRoute('admin_payment');
    }

    /**
     * @todo secure
     *
     * @Route("/{id}/decline/", name="admin_payment_decline")
     */
    public function decline(Payment $payment, EntityManagerInterface $em): RedirectResponse
    {
        $order = $payment->getOrder();

        if ($payment->getAmount() == $order->getAmount()) {
            $order->setPaymentStatus(Order::PAYMENT_CANCELLED);
        }

        $payment->setStatus(Payment::STATUS_CANCELLED);

        $em->flush();

        $this->addFlash('error', 'Платёж отклонён');

        return $this->redirectToRoute('admin_payment');
    }
}
