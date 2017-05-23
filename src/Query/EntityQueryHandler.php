<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Query;

use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\Queryable;
use UselessSoft\Queries\Doctrine\QueryHandlerInterface;
use UselessSoft\Queries\QueryInterface;

class EntityQueryHandler implements QueryHandlerInterface
{
    use Scream;

    /** @var Queryable */
    private $queryable;

    public function __construct(Queryable $queryable)
    {
        $this->queryable = $queryable;
    }

    public function fetch(QueryInterface $query)
    {
        assert($query instanceof EntityQuery);

        return $this->queryable->getEntityManager()
            ->find($query->getEntityName(), $query->getId());
    }

    public function supports(QueryInterface $query) : bool
    {
        return $query instanceof EntityQuery;
    }
}
