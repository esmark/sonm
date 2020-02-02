<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Cooperative;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/basket")
 *
 * IsGranted("ROLE_USER")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/", name="basket")
     */
    public function index(): Response
    {
        /** @var Cooperative[] $coops */
        $coops = [];

        /** @var Basket $basket */
        foreach ($this->getUser()->getBaskets() as $basket) {
            $coops[$basket->getProductVariant()->getCooperative()->getId()] = $basket->getProductVariant()->getCooperative();
        }

        return $this->render('basket/index.html.twig', [
            'coops' => $coops,
        ]);
    }

    /**
     * @Route("/add/", name="basket_add", methods={"POST"})
     */
    public function addItem(Request $request, EntityManagerInterface $em): JsonResponse
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
        $quantity  = (int) $request->request->get('quantity', 1);
        $variantId = $request->request->get('variant_id');

        try {
            $variant = $em->getRepository(ProductVariant::class)->findOneBy(['id' => Uuid::fromString($variantId)]);
        } catch (InvalidUuidStringException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Неверный вариант',
            ]);
        }

        if (empty($variant)) {
            $data = [
                'status' => 'error',
                'message' => 'Вариант не найден',
            ];

            return new JsonResponse($data);
        }

        if ($quantity < 0) {
            $quantity = 0;
        }

        if ($quantity > $variant->getQuantity() - $variant->getQuantityReserved()
            and $variant->getQuantity() - $variant->getQuantityReserved() > 0
        ) {
            $data = [
                'status' => 'error',
                'message' => 'Недопустимое кол-во',
            ];

            return new JsonResponse($data);
        }

        $basket = $em->getRepository(Basket::class)->findOneBy(['user' => $this->getUser(), 'productVariant' => $variant]);

        if (empty($basket)) {
            $basket = new Basket();
            $basket
                ->setUser($this->getUser())
                ->setProductVariant($variant)
            ;
        }

        $basket->setQuantity($quantity);

        if ($quantity == 0 or $request->request->has('remove')) {
            $em->remove($basket);
        } else {
            $em->persist($basket);
        }

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
