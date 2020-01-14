<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Smart\CoreBundle\Doctrine\RepositoryTrait;

class ItemRepository extends ServiceEntityRepository
{
    use RepositoryTrait\FindByQuery;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @param array $filters
     *
     * @return QueryBuilder
     */
    public function getFindQueryBuilder(array $filters = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c');

        if (!empty($filters['category']) and $filters['category'] >= 1) {
            $qb->andWhere('c.category = :category');
            $qb->setParameter('category', $filters['category']);
        }

        if (!empty($filters['search']) and strlen($filters['search']) >= 3) {
            $qb->andWhere('c.title LIKE :search');
            $qb->setParameter('search', '%'.$filters['search'].'%');
        }

        return $qb;
    }
}
