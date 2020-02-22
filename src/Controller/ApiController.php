<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Geo\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/geo/cities", name="api_geo_cities")
     */
    public function geoCities(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = [];

        $cities = $em->getRepository(City::class)->search($request->query->get('q'));

        foreach ($cities as $city) {
            $data['results'][] = [
                'id' => $city->getId(),
                'text' => (string) $city . ' (' . $city->getRegion()->getFullname() . ')',
            ];
        }

        return new JsonResponse($data);
    }
}
