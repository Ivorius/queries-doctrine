<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Doctrine;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\Specifications\TSpecificationQuery;
use UselessSoft\Queries\Exception\InvalidArgumentException;
use UselessSoft\Queries\QueryableInterface;
use UselessSoft\Queries\ResultSetInterface;

/**
 * @author David Matejka
 */
abstract class QueryObject implements QueryableInterface, QueryInterface
{
    use Scream;
    use TSpecificationQuery;

	/** @var callable[] */
	public $onPostFetch = [];

	/** @var Query */
	private $lastQuery;

	/** @var ResultSetInterface */
	private $lastResult;


	/**
	 * @param QueryableInterface
	 * @return ResultSetInterface
	 */
	public function fetch(QueryableInterface $queryable) : ResultSetInterface
	{
		if (!$queryable instanceof Queryable) {
			throw new InvalidArgumentException("\$queryable must be an instance of " . Queryable::class);
		}
		$this->getQuery($queryable)
			->setFirstResult(NULL)
			->setMaxResults(NULL);

		return $this->lastResult;
	}


	/**
	 * @param Queryable
	 * @return Query
	 * @internal
	 */
	public function getQuery(Queryable $repository) : Query
	{
		$qb = $this->createQuery($repository);
		$this->applySpecifications($qb, $qb->getRootAliases()[0]);

		$query = $qb->getQuery();
		$this->modifyQuery($query);

		if ($this->lastQuery && $this->lastQuery->getDQL() === $query->getDQL()) {
			$query = $this->lastQuery;
		}

		if ($this->lastQuery !== $query) {
			$this->lastResult = $this->createResultSet($query, $repository);
		}

		return $this->lastQuery = $query;
	}


	/**
	 * @internal
	 * @return Query
	 */
	public function getLastQuery() : Query
	{
		return $this->lastQuery;
	}


	/**
	 * @param Doctrine\ORM\Query
	 * @param Queryable
	 * @return ResultSetInterface
	 */
	protected function createResultSet(Query $query, Queryable $queryable) : ResultSetInterface
	{
		return new ResultSet($query, $this, $queryable);
	}


	/**
	 * @param Queryable
	 * @return QueryBuilder
	 */
	abstract protected function createQuery(Queryable $queryable) : QueryBuilder;


	/**
	 * @param Queryable
	 * @param \Traversable
	 * @internal
	 */
	public function queryFetched(Queryable $queryable, \Traversable $data) : void
	{
	    foreach ($this->onPostFetch as $postFetch) {
			$postFetch($this, $queryable, $data);
        }
	}

}
