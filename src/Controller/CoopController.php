<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cooperative;
use App\Entity\CooperativeHistory;
use App\Entity\CooperativeMember;
use App\Repository\CooperativeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/coop")
 */
class CoopController extends AbstractController
{
    /**
     * @Route("/", name="coop")
     */
    public function index(CooperativeRepository $cooperativeRepository): Response
    {
        return $this->render('coop/index.html.twig', [
            'coops' => $cooperativeRepository->findActive(),
        ]);
    }

    /**
     * @Route("/{name}/", name="coop_show")
     */
    public function show(string $name, Request $request, CooperativeRepository $cooperativeRepository, EntityManagerInterface $em): Response
    {
        /** @var Cooperative $coop */
        $coop = $cooperativeRepository->findOneBy(['name' => $name]);

        if (empty($coop)) {
            throw $this->createNotFoundException('Нет такого кооператива');
        }

        if ($request->query->has('entry_request')) {
            if ($coop->getMemberByUser($this->getUser())) {
                $this->addFlash('error', 'Уже участник');
            } else {
                $member = new CooperativeMember($coop, $this->getUser());

                $history = new CooperativeHistory();
                $history
                    ->setCooperative($coop)
                    ->setAction(CooperativeHistory::ACTION_MEMBER_REQUEST)
                    ->setUser($this->getUser())
                ;

                $em->persist($coop);
                $em->persist($member);
                $em->persist($history);
                $em->flush();

                $this->addFlash('success', 'Заявка на вступление в кооператив отправлена');
            }

            return $this->redirectToRoute('coop_show', ['name' => $coop->getName()]);
        }

        return $this->render('coop/show.html.twig', [
            'coop' => $coop,
        ]);
    }
}
