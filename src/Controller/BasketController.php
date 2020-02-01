<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Cooperative;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/basket")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/", name="basket")
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        /** @var Cooperative[] $coops */
        $coops = [];

        /** @var Basket $basket */
        foreach ($this->getUser()->getBaskets() as $basket) {
            $coops[$basket->getItem()->getCooperative()->getId()] = $basket->getItem()->getCooperative();
        }

        return $this->render('basket/index.html.twig', [
            'coops' => $coops,
        ]);
    }

    /**
     * @todo variant
     *
     * @Route("/add/item/{id}", name="basket_add_item", methods={"POST"})
     */
    public function addItem(Product $product, Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if (empty($user) or !$user->isMember()) {
            $data = [
                'status' => 'error',
                'message' => 'Добавлять товары в корзину могут только участники кооперативов',
            ];

            return new JsonResponse($data);
        }

        $error_msg = null;
        $quantity = (int) $request->request->get('quantity', 1);

        if ($quantity < 1) {
            $quantity = 1;
        }

        $basket = $em->getRepository(Basket::class)->findOneBy(['user' => $this->getUser(), 'item' => $product]);

        if (empty($basket)) {
            $basket = new Basket();
            $basket
                ->setUser($this->getUser())
                ->setItem($product)
            ;
        }

        $basket->setQuantity($quantity);

        $em->persist($basket);
        $em->flush();

        $data = [
            'status' => 'success',
        ];

        if ($error_msg) {
            $data = [
                'status' => 'error',
                'message' => $error_msg,
            ];
        }

        return new JsonResponse($data);
    }
}
