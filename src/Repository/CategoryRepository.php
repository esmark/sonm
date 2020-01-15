<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Smart\CoreBundle\Doctrine\RepositoryTrait;

class CategoryRepository extends ServiceEntityRepository
{
    use RepositoryTrait\FindByQuery;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
