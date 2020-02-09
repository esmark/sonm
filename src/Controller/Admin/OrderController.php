<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order")
 */
class OrderController extends AbstractController
{
    /**
     * @todo pagination
     *
     * @Route("/", name="admin_order")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/order/index.html.twig', [
            'orders' => $em->getRepository(Order::class)->findBy([], ['created_at' => 'DESC']),
        ]);
    }

    /**
     * @Route("/{id}/", name="admin_order_show")
     */
    public function show(Order $order): Response
    {
        return $this->render('admin/order/show.html.twig', [
            'order' => $order,
        ]);
    }
}
