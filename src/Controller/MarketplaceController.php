<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
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
     * @todo pagination
     *
     * @Route("/", name="marketplace")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)
            ->getFindQueryBuilder([
                'category' => $request->query->get('category'),
                'search' => $request->query->get('search'),
            ])
            ->getQuery()
            ->getResult()
        ;

        return $this->render('marketplace/index.html.twig', [
            'categories' => $em->getRepository(Category::class)->findBy([], ['position' => 'ASC', 'title' => 'ASC']),
            'products'   => $products,
        ]);
    }

    /**
     * @Route("/{id}/", name="marketplace_item")
     */
    public function item(Product $product): Response
    {
        return $this->render('marketplace/product.html.twig', [
            'product' => $product,
        ]);
    }
}
