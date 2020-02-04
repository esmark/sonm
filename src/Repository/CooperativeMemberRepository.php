<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CooperativeMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CooperativeMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CooperativeMember::class);
    }

    public function findForPending($id): ?CooperativeMember
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.id = :id')
        ;

        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->eq('e.status', ':status_pending_assoc'),
            $qb->expr()->eq('e.status', ':status_pending_real'),
        ));

        $qb
            ->setParameter('id', $id)
            ->setParameter('status_pending_assoc', CooperativeMember::STATUS_PENDING_ASSOC)
            ->setParameter('status_pending_real',  CooperativeMember::STATUS_PENDING_REAL)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
