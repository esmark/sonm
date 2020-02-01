<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Address;
use App\Entity\User;
use App\Form\Type\AddressFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/address")
 */
class AddressController extends AbstractController
{
    /**
     * @Route("/", name="account_address")
     */
    public function address(EntityManagerInterface $em): Response
    {
        return $this->render('account/address/index.html.twig', [
            'addresses' => $em->getRepository(Address::class)->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/create/", name="account_address_create")
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

    /**
     * @Route("/{id}/", name="account_address_edit")
     */
    public function edit(Address $address, Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getId() !== $address->getUser()->getId()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressFormType::class, $address);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_address');
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Адрес обновлён');

                return $this->redirectToRoute('account_address');
            }
        }

        return $this->render('account/address/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
