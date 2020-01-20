<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Program;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('program/index.html.twig', [
            'programs' => $em->getRepository(Program::class)->findBy(['is_enabled' => true], ['title' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}/", name="program_show")
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }
}
