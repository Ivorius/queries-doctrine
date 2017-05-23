<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\QueryBuilder;

interface QueryableInterface
{
    /**
     * @param string|null
     * @param string|null
     * @param string|null
     * @return QueryBuilder
     */
    public function createQueryBuilder(?string $entityClass = NULL, ?string $alias = NULL, ?string $indexBy = NULL) : QueryBuilder;


    public function createQuery(string $query) : Query;

    /**
     * @return EntityManager
     */
    public function getEntityManager() : EntityManager;
}
