<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Cooperative;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CooperativeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cooperative::class);
    }

    /**
     * @return Cooperative[]|null
     */
    public function findActive(?array $orderBy = null): ?array
    {
        if (empty($orderBy)) {
            $orderBy = ['id' => 'DESC'];
        }

        $qb = $this->createQueryBuilder('e')
            ->where('e.status = :status')
            ->setParameter('status', Cooperative::STATUS_ACTIVE)
        ;

        foreach ($orderBy as $field => $order) {
            $qb->addOrderBy('e.' . $field, $order);
        }

        return $qb->getQuery()->getResult();
    }
}
