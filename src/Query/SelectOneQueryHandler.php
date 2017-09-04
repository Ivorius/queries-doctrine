<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Query;

use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\Queryable;
use UselessSoft\Queries\Doctrine\QueryHandlerInterface;
use UselessSoft\Queries\QueryInterface;

class SelectOneQueryHandler implements QueryHandlerInterface
{
    use Scream;

    /** @var Queryable */
    private $queryable;

    public function __construct(Queryable $queryable)
    {
        $this->queryable = $queryable;
    }

    public function handle(QueryInterface $query)
    {
        assert($query instanceof SelectOneQuery);

        $qb = $this->queryable->createQueryBuilder($query->getEntityClass(), 'e');
        foreach ($query->getFilters() as $filter) {
            list ($field, $value) = $filter;
            if ($value === NULL && $field instanceof \Closure) {
                $field($qb, 'e');
            } else {
                $paramName = 'param_' . (count($qb->getParameters()) + 1);
                $fieldName = strpos($field, '.') === false ? sprintf('%s.%s', $qb->getRootAliases()[0], $field) : $field;
                $qb->andWhere(sprintf('%s = :%s', $fieldName, $paramName));
                $qb->setParameter($paramName, $value);
            }
        }
        foreach ($query->getOrderBy() as $field => $direction) {
            $qb->addOrderBy($field, $direction);
        }
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function supports(QueryInterface $query) : bool
    {
        return $query instanceof SelectOneQuery;
    }

}
