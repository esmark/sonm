<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index(): RedirectResponse
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_show', ['id' => $this->getUser()->getId()]);
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/{id}/", name="user_show")
     */
    public function userShow(User $user, EntityManagerInterface $em): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
