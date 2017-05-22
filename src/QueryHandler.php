<?php

declare(strict_types=1);

namespace Librette\Doctrine\Queries;

use Kdyby\StrictObjects\Scream;
use Librette\Queries\QueryInterface as BaseQuery;
use Librette\Queries\QueryHandlerInterface;
use Librette\Queries\ResultSetInterface;

/**
 * @author David Matejka
 */
class QueryHandler implements QueryHandlerInterface
{
    use Scream;

	/** @var Queryable */
	protected $queryable;


	/**
	 * @param Queryable
	 */
	public function __construct(Queryable $queryable)
	{
		$this->queryable = $queryable;
	}


	public function supports(BaseQuery $query) : bool
	{
		return $query instanceof QueryInterface;
	}


	/**
	 * @param BaseQuery
	 * @return mixed|ResultSetInterface
	 */
	public function fetch(BaseQuery $query)
	{
		return $query->fetch($this->queryable);
	}

}
