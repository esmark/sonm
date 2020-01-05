<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserChangePasswordFormType;
use App\Form\Type\UserFormType;
use App\Repository\UserRepository;
use App\Utils\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * Route("/", name="account")
     */
    public function index()
    {
        return $this->render('account/index.html.twig', [

        ]);
    }
    
    /**
     * @Route("/", name="account_profile")
     */
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserFormType::class, $this->getUser());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($this->getUser());
                $em->flush();

                $this->addFlash('success', 'Основные данные профиля обновлены');

                return $this->redirectToRoute('account_profile');
            }
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/password/", name="account_password")
     */
    public function password(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserChangePasswordFormType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('update')->isClicked() and $form->isValid()) {
                /** @var User $user */
                $user = $this->getUser();

                $currentPassword = $form->get('current_password')->getData();

                if (!$encoder->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('error', 'Неверно указан текущий пароль');

                    return $this->redirectToRoute('account_password');
                }

                $validator = new UserValidator();

                try {
                    $password = $form->get('password')->getData();

                    $validator->validatePassword($password);

                    $user->setPassword(
                        $encoder->encodePassword($user, $password)
                    );

                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success', 'Пароль обновлён');
                } catch (\InvalidArgumentException $e) {
                    $this->addFlash('error', $e->getMessage());
                }

                return $this->redirectToRoute('account_password');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/geoposition/", name="account_geoposition")
     */
    public function geoposition(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $user
                ->setLatitude((float) $request->request->get('latitude'))
                ->setLongitude((float) $request->request->get('longitude'))
            ;

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Координаты сохранены.');

            return $this->redirectToRoute('account_geoposition');
        }

        return $this->render('account/geoposition.html.twig', [
            'latitude'  => $user->getLatitude(),
            'longitude' => $user->getLongitude(),
        ]);
    }

    /**
     * @Route("/telegram/", name="account_telegram")
     */
    public function telegram(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->query->has('remove')) {
            $user = $this->getUser();

            $user->setTelegramUserId(null);
            $user->setTelegramUsername(null);

            $em->flush();

            return $this->redirectToRoute('account_telegram');
        }

        $countdown = 0;

        if ($this->getUser()->getTelegramUsername()) {
            $code = null;
        } else {
            $cache = new FilesystemAdapter();

            $code = $cache->get('connect_telegram_account_user'.$this->getUser()->getId()->serialize(), function (ItemInterface $item) {
                $item->expiresAfter(60 * 2);

                return random_int(10000, 99999);
            });

            $user_id = $cache->get('connect_telegram_account_code'.$code, function (ItemInterface $item) {
                $item->expiresAfter(60 * 2);

                return $this->getUser()->getId()->serialize();
            });

            /** @var CacheItem $code_item */
            $code_item = $cache->getItem('connect_telegram_account_user'.$this->getUser()->getId()->serialize());

            $countdown = $code_item->getMetadata()['expiry'] - time();
        }

        return $this->render('account/telegram.html.twig', [
            'code' => $code,
            'countdown' => $countdown,
        ]);
    }
}
