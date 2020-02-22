<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Worksheet;
use App\Entity\Cooperative;
use App\Entity\CooperativeHistory;
use App\Entity\CooperativeMember;
use App\Entity\Geo\City;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\User;
use App\Form\Type\ProductFormType;
use App\Form\Type\UserChangePasswordFormType;
use App\Form\Type\UserFormType;
use App\Form\Type\UserLocationFormType;
use App\Form\Type\UserWorksheetPurposeFormType;
use App\Form\Type\WorksheetFormType;
use App\Repository\UserRepository;
use App\Utils\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use GeoIp2\Exception\AddressNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
     * @Route("/", name="account")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('account_profile');
    }

    /**
     * @Route("/balance/", name="account_balance")
     */
    public function balance(): Response
    {
        return $this->render('account/balance.html.twig', [

        ]);
    }

    /**
     * @todo историю
     *
     * @Route("/product/new/{coop}/", name="account_product_new")
     */
    public function productNew(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
    {
        $isAllowAccess = false;
        foreach ($coop->getMembers() as $member) {
            if ($member->getUser() == $this->getUser()
                and (
                    $member->getStatus() == CooperativeMember::STATUS_CHAIRMAN
                    or $member->isIsAllowMarketplace()
                )
            ) {
                $isAllowAccess = true;

                break;
            }
        }

        if (!$isAllowAccess) {
            $this->addFlash('error', 'Нет доступа для добавления товаров');

            return $this->redirectToRoute('account_coop_show', [
                'id'  => $coop->getId(),
                'tab' => 'nav-product-tab',
            ]);
        }

        $variant = new ProductVariant();
        $variant
            ->setCooperative($coop)
            ->setUser($this->getUser())
        ;

        $product = new Product();
        $product
            ->setCooperative($coop)
            ->setTaxRate($coop->getTaxRateDefault())
            ->setUser($this->getUser())
            ->addVariant($variant)
        ;

        $form = $this->createForm(ProductFormType::class, $product);
        $form->remove('update');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_coop_show', [
                    'id' => $coop->getId(),
                    'tab' => 'nav-product-tab',
                ]);
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $em->persist($product);
                $em->flush();

                $this->addFlash('success', 'Товар добавлен');

                return $this->redirectToRoute('account_coop_show', [
                    'id' => $coop->getId(),
                    'tab' => 'nav-product-tab',
                ]);
            }
        }

        return $this->render('account/product_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @todo историю
     *
     * @Route("/product/{id}/", name="account_product_edit")
     */
    public function productEdit(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $coop = $product->getCooperative();

        $isAllowAccess = false;
        foreach ($coop->getMembers() as $member) {
            if ($member->getUser() == $this->getUser()
                and (
                    $member->getStatus() == CooperativeMember::STATUS_CHAIRMAN
                    or $member->isIsAllowMarketplace()
                )
            ) {
                $isAllowAccess = true;

                break;
            }
        }

        if (!$isAllowAccess) {
            $this->addFlash('error', 'Нет доступа для изменения товаров');

            return $this->redirectToRoute('account_coop_show', [
                'id'  => $coop->getId(),
                'tab' => 'nav-product-tab',
            ]);
        }

        $coop = $product->getCooperative();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_coop_show', [
                    'id'  => $coop->getId(),
                    'tab' => 'nav-product-tab',
                ]);
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($product);
                $em->flush();

                $this->addFlash('success', 'Товар обновлён');

                return $this->redirectToRoute('account_coop_show', [
                    'id'  => $coop->getId(),
                    'tab' => 'nav-product-tab',
                ]);
            }
        }

        return $this->render('account/product_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/", name="account_profile")
     */
    public function profile(Request $request, EntityManagerInterface $em, ContainerInterface $container): Response
    {
        $geoIpService = $container->get('cravler_max_mind_geo_ip.service.geo_ip_service');

        $city = '-';
        try {
            $record = $geoIpService->getRecord($request->getClientIp(), 'city', ['locales' => ['ru']]);
            $city = $record->city->name;
        } catch (AddressNotFoundException $e) {
            // dummy
        } catch (\InvalidArgumentException $e) {
            // dummy
        }

        $form = $this->createForm(UserFormType::class, $this->getUser());

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('save')->isClicked() and $form->isValid()) {
                $em->persist($this->getUser());
                $em->flush();

                $this->addFlash('success', 'Основные данные профиля обновлены');

                return $this->redirectToRoute('account_profile');
            }
        }

        return $this->render('account/profile.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/worksheet/", name="account_worksheet")
     */
    public function worksheet(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserWorksheetPurposeFormType::class, $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('save')->isClicked()) {
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Анкета обновлена');

                return $this->redirectToRoute('account_worksheet');
            }
        }

        return $this->render('account/worksheet.html.twig', [
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

        $form = $this->createForm(UserLocationFormType::class, $user);

        $prevCity = $user->getCity();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            // @todo пока в ручную подставляется город, по хорошему надо сделать кастомный EntityType, который будет дружить с аяксом.
            $user->setCity($em->find(City::class, (int) $request->request->get('user_location')['city']));

            if ($user->getCity() and $prevCity != $user->getCity()) {
                $user
                    ->setLatitude($user->getCity()->getLatitude())
                    ->setLongitude($user->getCity()->getLongitude())
                ;
            }

            if ($form->get('save')->isClicked() and $form->isValid()) {
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Местоположение обновлено.');

                return $this->redirectToRoute('account_geoposition');
            }
        }

        return $this->render('account/geoposition.html.twig', [
            'form' => $form->createView(),
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

            $userId = $cache->get('connect_telegram_account_code'.$code, function (ItemInterface $item) {
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
