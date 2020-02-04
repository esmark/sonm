<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\Cooperative;
use App\Entity\CooperativeHistory;
use App\Entity\CooperativeMember;
use App\Entity\Order;
use App\Form\Type\CooperativeCreateFormType;
use App\Form\Type\CooperativeFormType;
use App\Form\Type\CooperativeMemberFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/coop")
 */
class CoopController extends AbstractController
{
    /**
     * @Route("/", name="account_coop")
     */
    public function coop(EntityManagerInterface $em): Response
    {
        return $this->render('account/coop/index.html.twig', [
            'members' => $em->getRepository(CooperativeMember::class)->findBy(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/create/", name="account_coop_create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
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

        return $this->render('account/coop/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/", name="account_coop_show")
     */
    public function show(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
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

        return $this->render('account/coop/show.html.twig', [
            'coop'          => $coop,
            'is_allow_edit' => $isAllowEdit,
        ]);
    }

    /**
     * @Route("/{id}/edit/", name="account_coop_edit")
     */
    public function edit(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
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

        return $this->render('account/coop/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @todo права доступа
     *
     * @Route("/member/{id}/", name="account_coop_member")
     */
    public function member(CooperativeMember $member, Request $request, EntityManagerInterface $em): Response
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

        return $this->render('account/coop/member.html.twig', [
            'form'   => $form->createView(),
            'member' => $member,
        ]);
    }

    /**
     * @Route("/{id}/order/", name="account_coop_orders")
     */
    public function orders(Cooperative $coop, EntityManagerInterface $em): Response
    {
        return $this->render('account/coop/orders.html.twig', [
            'coop'   => $coop,
            'orders' => $em->getRepository(Order::class)->findBy(['cooperative' => $coop->getId()], ['created_at' => 'DESC']),
        ]);
    }
}
