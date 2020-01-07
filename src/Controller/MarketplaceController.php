<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/marketplace")
 */
class MarketplaceController extends AbstractController
{
    /**
     * @Route("/", name="marketplace")
     */
    public function index(): Response
    {
        return $this->render('marketplace/index.html.twig');
    }
}
