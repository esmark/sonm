<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $items = $em->getRepository(Item::class)
            ->getFindQueryBuilder([
                'category' => $request->query->get('category'),
                'search' => $request->query->get('search'),
            ])
            ->getQuery()
            ->getResult()
        ;

        return $this->render('marketplace/index.html.twig', [
            'categories' => $em->getRepository(Category::class)->findBy([], ['position' => 'ASC', 'title' => 'ASC']),
            'items'      => $items,
        ]);
    }

    /**
     * @Route("/{id}/", name="marketplace_item")
     */
    public function item(Item $item): Response
    {
        return $this->render('marketplace/item.html.twig', [
            'item' => $item,
        ]);
    }
}
