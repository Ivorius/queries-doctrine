<?php

declare(strict_types=1);

namespace Librette\Doctrine\Queries;

use Librette\Queries\QueryInterface as BaseQuery;
use Librette\Queries\QueryHandlerInterface;
use Librette\Queries\ResultSetInterface;
use Nette\Object;

/**
 * @author David Matejka
 */
class QueryHandler extends Object implements QueryHandlerInterface
{

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
