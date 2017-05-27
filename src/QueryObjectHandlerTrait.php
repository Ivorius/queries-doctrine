<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use UselessSoft\Queries\Doctrine\Specifications\SpecificationQueryHandlerTrait;
use UselessSoft\Queries\QueryInterface as BaseQueryInterface;
use UselessSoft\Queries\ResultSetInterface as BaseResultSetInterface;

trait QueryObjectHandlerTrait
{
    use SpecificationQueryHandlerTrait;


    /**
     * @param QueryInterface
     * @return ResultSetInterface
     */
    public function handle(BaseQueryInterface $queryObject) : BaseResultSetInterface
    {
        assert($queryObject instanceof QueryObjectInterface);

        $query = $this->getQuery($queryObject)
            ->setFirstResult(NULL)
            ->setMaxResults(NULL);

        return $this->createResultSet($queryObject, $query);
    }


    /**
     * @param QueryObjectInterface
     * @return QueryInterface
     * @internal
     */
    public function getQuery(QueryObjectInterface $queryObject) : Query
    {
        $qb = $this->createQuery($queryObject);
        $this->applySpecifications($queryObject->getSpecifications(), $qb, $qb->getRootAliases()[0]);

        $query = $qb->getQuery();
        $this->modifyQuery($queryObject->getSpecifications(), $query);

        return $query;
    }


    /**
     * @param QueryObjectInterface
     * @param Query
     * @param Queryable
     * @return ResultSetInterface
     */
    protected function createResultSet(QueryObjectInterface $queryObject, Query $query) : BaseResultSetInterface
    {
        return new ResultSet($query, $queryObject, $this->getQueryable());
    }


    /**
     * @param QueryObjectInterface
     * @return QueryBuilder
     */
    abstract protected function createQuery(QueryObjectInterface $queryObject) : QueryBuilder;

}
