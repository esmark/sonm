<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Basket;
use App\Entity\Cooperative;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    /**
     * @param User        $user
     * @param Cooperative $coop
     *
     * @return Basket[]
     */
    public function findByUserAndCoop(User $user, Cooperative $coop): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.user = :user')
            ->join('e.productVariant', 'v', 'WITH', 'v.cooperative = :coop')
            ->setParameter('user', $user)
            ->setParameter('coop', $coop)
            ->getQuery()
            ->getResult()
        ;
    }
}
