<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cooperative;
use App\Entity\CooperativeHistory;
use App\Entity\User;
use App\Repository\CooperativeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/user/", name="admin_user")
     */
    public function userIndex(Request $request, EntityManagerInterface $em): Response
    {
        return $this->render('admin/user_index.html.twig', [
            'users' => $em->getRepository(User::class)->findBy([], ['created_at' => 'DESC']),
        ]);
    }

    /**
     * @Route("/coop/", name="admin_coop")
     */
    public function coopIndex(CooperativeRepository $cooperativeRepository): Response
    {
        return $this->render('admin/coop_index.html.twig', [
            'coops' => $cooperativeRepository->findBy([], ['created_at' => 'DESC']),
        ]);
    }

    /**
     * @Route("/coop/{id}/", name="admin_coop_show")
     */
    public function coopShow(Cooperative $coop, Request $request, EntityManagerInterface $em): Response
    {
        if ($coop->getStatus() == Cooperative::STATUS_PENDING) {
            if ($request->query->has('approve')) {
                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_UPDATE)
                    ->setUser($this->getUser())
                    ->setOldValue(['status' => $coop->getStatus()])
                    ->setNewValue(['status' => Cooperative::STATUS_ACTIVE])
                ;

                $coop->setStatus(Cooperative::STATUS_ACTIVE);

                $this->addFlash('success', 'Заявка на создание кооператива одобрена');

                $em->persist($history);
                $em->flush();

                return $this->redirectToRoute('admin_coop_show', ['id' => $coop->getId()]);
            }

            if ($request->query->has('decline')) {
                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_UPDATE)
                    ->setUser($this->getUser())
                    ->setOldValue(['status' => $coop->getStatus()])
                    ->setNewValue(['status' => Cooperative::STATUS_DECLINE])
                ;

                $coop->setStatus(Cooperative::STATUS_DECLINE);

                $this->addFlash('error', 'Заявка на создание кооператива отклонена');

                $em->persist($history);
                $em->flush();

                return $this->redirectToRoute('admin_coop_show', ['id' => $coop->getId()]);
            }
        }

        return $this->render('admin/coop_show.html.twig', [
            'coop' => $coop,
        ]);
    }
}
