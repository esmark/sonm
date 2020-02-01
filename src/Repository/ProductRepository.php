<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Smart\CoreBundle\Doctrine\RepositoryTrait;

class ProductRepository extends ServiceEntityRepository
{
    use RepositoryTrait\FindByQuery;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param array $filters
     *
     * @return QueryBuilder
     */
    public function getFindQueryBuilder(array $filters = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('e');

        if (!empty($filters['category']) and $filters['category'] >= 1) {
            $qb->andWhere('e.category = :category');
            $qb->setParameter('category', $filters['category']);
        }

        if (!empty($filters['search']) and strlen($filters['search']) >= 3) {
            $qb->andWhere('e.title LIKE :search');
            $qb->setParameter('search', '%'.$filters['search'].'%');
        }

        return $qb;
    }
}
