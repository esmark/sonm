<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserRegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @todo подтверждение по емаил
     *
     * @Route("/register/", name="register")
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserRegisterFormType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('register')->isClicked() and $form->isValid()) {
                /** @var User $user */
                $user = $form->getData();

                $encodedPassword = $encoder->encodePassword($user, $user->getPlainPassword());

                $user->setPassword($encodedPassword);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Регистрация успешна. Теперь можете войти на сайт');

                return $this->redirectToRoute('security_login');
            }
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
