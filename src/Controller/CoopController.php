<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cooperative;
use App\Repository\CooperativeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(string $name, CooperativeRepository $cooperativeRepository): Response
    {
        $coop = $cooperativeRepository->findOneBy(['name' => $name]);

        if (empty($coop)) {
            throw $this->createNotFoundException('Нет такого кооператива');
        }

        return $this->render('coop/show.html.twig', [
            'coop' => $coop,
        ]);
    }
}
