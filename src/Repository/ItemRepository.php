<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Smart\CoreBundle\Doctrine\RepositoryTrait;

class ItemRepository extends EntityRepository
{
    use RepositoryTrait\FindByQuery;
}
