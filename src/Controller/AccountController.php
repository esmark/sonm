<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Worksheet;
use App\Entity\Address;
use App\Entity\Cooperative;
use App\Entity\CooperativeHistory;
use App\Entity\CooperativeMember;
use App\Entity\Item;
use App\Entity\User;
use App\Form\Type\AddressFormType;
use App\Form\Type\CooperativeCreateFormType;
use App\Form\Type\CooperativeFormType;
use App\Form\Type\CooperativeMemberFormType;
use App\Form\Type\ItemFormType;
use App\Form\Type\UserChangePasswordFormType;
use App\Form\Type\UserFormType;
use App\Form\Type\UserWorksheetPurposeFormType;
use App\Form\Type\WorksheetFormType;
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
     * @Route("/", name="account")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('account_profile');
    }

    /**
     * @Route("/address/", name="account_address")
     */
    public function address(EntityManagerInterface $em): Response
    {
        return $this->render('account/address.html.twig', [
            'addresses' => $em->getRepository(Address::class)->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/address/new/", name="account_address_new")
     */
    public function addressNew(Request $request, EntityManagerInterface $em): Response
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

        return $this->render('account/address_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/address/{id}/", name="account_address_edit")
     */
    public function addressEdit(Address $address, Request $request, EntityManagerInterface $em): Response
    {
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

        return $this->render('account/address_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/coop/", name="account_coop")
     */
    public function coop(EntityManagerInterface $em): Response
    {
        return $this->render('account/coop.html.twig', [
            'members' => $em->getRepository(CooperativeMember::class)->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/coop/new/", name="account_coop_new")
     */
    public function coopNew(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CooperativeCreateFormType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $coop = $form->getData();

                $member = new CooperativeMember();
                $member
                    ->setCooperative($coop)
                    ->setStatus(CooperativeMember::STATUS_CHAIRMAN)
                    ->setUser($this->getUser())
                ;

                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_CREATE)
                    ->setUser($this->getUser())
                ;

                $history2 = new CooperativeHistory();
                $history2
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_MEMBER_ADD)
                    ->setUser($this->getUser())
                ;

                $em->persist($coop);
                $em->persist($member);
                $em->persist($history);
                $em->persist($history2);
                $em->flush();

                $this->addFlash('success', 'Заявка на добавление кооператива отправлена');

                return $this->redirectToRoute('account_coop');
            }
        }

        return $this->render('account/coop_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/coop/{id}/", name="account_coop_show")
     */
    public function coopShow(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
    {
        $isMember = false;
        $isAllowEdit = false;

        foreach ($coop->getMembers() as $member) {
            if ($member->getUser() == $this->getUser()) {
                $isMember = true;

                if ($member->getStatus() == CooperativeMember::STATUS_CHAIRMAN) {
                    $isAllowEdit = true;
                }

                break;
            }
        }

        if (!$isMember) {
            return $this->redirectToRoute('account_coop');
        }

        if ($isAllowEdit and $coop->getStatus() == Cooperative::STATUS_DECLINE) {
            if ($request->query->has('approve_request')) {
                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_UPDATE)
                    ->setUser($this->getUser())
                    ->setOldValue(['status' => $coop->getStatus()])
                    ->setNewValue(['status' => Cooperative::STATUS_PENDING])
                ;

                $coop->setStatus(Cooperative::STATUS_PENDING);

                $this->addFlash('success', 'Повторная Заявка на создание кооператива отправлена');

                $em->persist($history);
                $em->flush();

                return $this->redirectToRoute('account_coop_show', ['id' => $coop->getId()]);
            }
        }

        if ($isAllowEdit and $request->query->has('approve_member')) {
            /** @var CooperativeMember $member */
            $member = $em->getRepository(CooperativeMember::class)->findForPending($request->query->get('approve_member'));

            if ($member) {
                if ($member->getStatus() == CooperativeMember::STATUS_PENDING_ASSOC) {
                    $member->setStatus(CooperativeMember::STATUS_ASSOC);
                } elseif ($member->getStatus() == CooperativeMember::STATUS_PENDING_REAL) {
                    $member->setStatus(CooperativeMember::STATUS_REAL);
                } else {
                    throw new \Exception('Bad pending status: '.$member->getStatus());
                }

                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_MEMBER_ADD)
                    ->setNewValue([
                        'member_name'   => (string) $member->getUser(),
                        'member_status' => $member->getStatusAsText(),
                        'member_id  '   => (string) $member->getUser()->getId(),
                    ])
                    ->setUser($this->getUser())
                ;

                $em->persist($member);
                $em->persist($history);

                $em->flush();

                $this->addFlash('success', 'Участник добавлен, как действительный член');
            } else {
                $this->addFlash('error', 'Неверно указан участник');
            }

            return $this->redirectToRoute('account_coop_show', [
                'id' => $coop->getId(),
                'tab' => 'nav-members-tab',
            ]);
        }

        if ($isAllowEdit and $request->query->has('decline_member')) {
            $member = $em->getRepository(CooperativeMember::class)->findForPending($request->query->get('decline_member'));

            if ($member) {
                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_MEMBER_DECLINE)
                    ->setNewValue([
                        'member_name' => (string) $member->getUser(),
                        'member_id  ' => (string) $member->getUser()->getId(),
                    ])
                    ->setUser($this->getUser())
                ;

                $em->persist($history);
                $em->flush();

                $em->remove($member);
                $em->flush();

                $this->addFlash('success', 'Заявка на вступление в кооператив отклонена');
            } else {
                $this->addFlash('error', 'Неверно указан участник');
            }

            return $this->redirectToRoute('account_coop_show', [
                'id' => $coop->getId(),
                'tab' => 'nav-members-tab',
            ]);
        }

        return $this->render('account/coop_show.html.twig', [
            'coop'          => $coop,
            'is_allow_edit' => $isAllowEdit,
        ]);
    }

    /**
     * @Route("/coop/{id}/edit/", name="account_coop_edit")
     */
    public function coopEdit(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
    {
        $isAllowEdit = false;
        foreach ($coop->getMembers() as $member) {
            if ($member->getUser() == $this->getUser() and $member->getStatus() == CooperativeMember::STATUS_CHAIRMAN) {
                $isAllowEdit = true;

                break;
            }
        }

        if (!$isAllowEdit) {
            return $this->redirectToRoute('account_coop_show', ['id' => $coop->getId()]);
        }

        $form = $this->createForm(CooperativeFormType::class, $coop);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_coop_show', ['id' => $coop->getId()]);
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $coop = $form->getData();

                $uow = $em->getUnitOfWork();
                $uow->computeChangeSets();

                if ($uow->isEntityScheduled($coop)) {
                    $old = [];
                    $new = [];
                    foreach ($uow->getEntityChangeSet($coop) as $key => $val) {
                        if ($val[0] instanceof \DateTime) {
                            $val[0] = $val[0]->format('Y-m-d H:i:s');
                        }

                        if ($val[1] instanceof \DateTime) {
                            $val[1] = $val[1]->format('Y-m-d H:i:s');
                        }

                        $old[$key] = (string) $val[0];
                        $new[$key] = (string) $val[1];
                    }

                    $history = new CooperativeHistory();
                    $history
                        ->setCooperative($coop)
                        ->setAction(CooperativeHistory::ACTION_UPDATE)
                        ->setUser($this->getUser())
                        ->setOldValue($old)
                        ->setNewValue($new)
                    ;

                    $em->persist($history);
                    $em->flush();

                    $this->addFlash('success', 'Данные кооператива ообновлены');
                }

                return $this->redirectToRoute('account_coop_show', ['id' => $coop->getId()]);
            }
        }

        return $this->render('account/coop_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @todo права доступа
     *
     * @Route("/coop/member/{id}/", name="account_coop_member")
     */
    public function coopMember(CooperativeMember $member, Request $request, EntityManagerInterface $em): Response
    {
        $coop = $member->getCooperative();
        $form = $this->createForm(CooperativeMemberFormType::class, $member);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_coop_show', [
                    'id' => $coop->getId(),
                    'tab' => 'nav-members-tab',
                ]);
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($form->getData());
                $em->flush();

                $this->addFlash('success', 'Участник обновлён: '.(string) $member->getUser());

                return $this->redirectToRoute('account_coop_show', [
                    'id' => $coop->getId(),
                    'tab' => 'nav-members-tab',
                ]);
            }
        }

        return $this->render('account/coop_member.html.twig', [
            'form'   => $form->createView(),
            'member' => $member,
        ]);
    }
    
    /**
     * @todo историю
     *
     * @Route("/item/new/{coop}/", name="account_item_new")
     */
    public function itemNew(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
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
                'tab' => 'nav-item-tab',
            ]);
        }

        $item = new Item();
        $item
            ->setCooperative($coop)
            ->setUser($this->getUser())
        ;

        $form = $this->createForm(ItemFormType::class, $item);
        $form->remove('update');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_coop_show', [
                    'id' => $coop->getId(),
                    'tab' => 'nav-item-tab',
                ]);
            }

            if ($form->get('create')->isClicked() and $form->isValid()) {
                $em->persist($item);
                $em->flush();

                $this->addFlash('success', 'Товар добавлен');

                return $this->redirectToRoute('account_coop_show', [
                    'id' => $coop->getId(),
                    'tab' => 'nav-item-tab',
                ]);
            }
        }

        return $this->render('account/item_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @todo историю
     *
     * @Route("/item/{id}/", name="account_item_edit")
     */
    public function itemEdit(Item $item, Request $request, EntityManagerInterface $em): Response
    {
        $coop = $item->getCooperative();

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
                'tab' => 'nav-item-tab',
            ]);
        }

        $coop = $item->getCooperative();
        $form = $this->createForm(ItemFormType::class, $item);
        $form->remove('create');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('account_coop_show', [
                    'id'  => $coop->getId(),
                    'tab' => 'nav-item-tab',
                ]);
            }

            if ($form->get('update')->isClicked() and $form->isValid()) {
                $em->persist($item);
                $em->flush();

                $this->addFlash('success', 'Товар обновлён');

                return $this->redirectToRoute('account_coop_show', [
                    'id'  => $coop->getId(),
                    'tab' => 'nav-item-tab',
                ]);
            }
        }

        return $this->render('account/item_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/", name="account_profile")
     */
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
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
