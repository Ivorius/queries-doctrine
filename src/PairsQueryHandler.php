<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\QueryInterface;

class PairsQueryHandler implements QueryHandlerInterface
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
        assert($query instanceof PairsQuery);

        return $this->queryable->getEntityManager()
            ->getRepository($query->getEntityName())
            ->findPairs($query->getFilter(), $query->getValue(), $query->getOrderBy(), $query->getKey());
    }

    public function supports(QueryInterface $query) : bool
    {
        return $query instanceof PairsQuery;
    }

}
