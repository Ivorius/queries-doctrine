<?php

declare(strict_types=1);

namespace Librette\Doctrine\Queries;

use Kdyby\StrictObjects\Scream;
use Librette\Queries\QueryInterface;

class SelectOneQueryHandler implements QueryHandlerInterface
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
        assert($query instanceof SelectOneQuery);

        $qb = $this->queryable->createQueryBuilder($query->getEntityClass(), 'e');
        foreach ($query->getFilters() as $filter) {
            list ($field, $value) = $filter;
            if ($value === NULL && $field instanceof \Closure) {
                $field($qb, 'e');
            } else {
                $qb->whereCriteria([$field => $value]);
            }
        }
        foreach ($query->getOrderBy() as $field => $direction) {
            $qb->autoJoinOrderBy($field, $direction);
        }
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function supports(QueryInterface $query) : bool
    {
        return $query instanceof SelectOneQuery;
    }

}
