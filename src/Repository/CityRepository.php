<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Geo\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @param string $str
     * @param int    $limit
     *
     * @return City[]
     */
    public function search($str, $limit = 20): array
    {
        if (empty($str)) {
            return [];
        }

        return $this->createQueryBuilder('e')
            ->where('e.name_canonical LIKE :str')
            ->orderBy('e.population', 'DESC')
            ->addOrderBy('e.name_canonical', 'ASC')
            ->setParameter('str', "$str%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
