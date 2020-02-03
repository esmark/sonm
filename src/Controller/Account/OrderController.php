<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Order;
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
     * @Route("/create/", name="account_order_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $address = new Address();
        $address
            ->setUser($user)
            ->setLastname($user->getLastname())
            ->setFirstname($user->getFirstname())
            ->setPatronymic($user->getPatronymic())
            ->setPhone($user->getPhone())
        ;

        $form = $this->createForm(AddressFormType::class, $address);
        $form->remove('update');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_address');
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());

                $em->flush();

                $this->addFlash('success', 'Адрес добавлен');

                return $this->redirectToRoute('account_address');
            }
        }

        return $this->render('account/address/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
