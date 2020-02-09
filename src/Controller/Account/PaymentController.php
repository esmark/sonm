<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @todo pagination
     *
     * @Route("/", name="account_payment")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('account/payment/index.html.twig', [
            'payments' => $em->getRepository(Payment::class)->findBy(
                ['user' => $this->getUser()],
                ['created_at' => 'DESC']
            ),
        ]);
    }
}
