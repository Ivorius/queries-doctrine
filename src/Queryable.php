<?php

declare(strict_types=1);

namespace Librette\Doctrine\Queries;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Kdyby\Doctrine\QueryBuilder;
use Librette\Queries\QueryableInterface;
use Librette\Queries\QueryHandlerInterface;
use Nette\Object;

/**
 * @author David Matejka
 */
class Queryable extends Object implements QueryableInterface
{

	/** @var EntityManager */
	protected $entityManager;

	/** @var QueryHandlerInterface */
	private $queryHandler;


	/**
	 * @param EntityManager
	 * @param QueryHandlerInterface
	 */
	public function __construct(EntityManager $entityManager, QueryHandlerInterface $queryHandler)
	{
		$this->entityManager = $entityManager;
		$this->queryHandler = $queryHandler;
	}


	/**
	 * @param string|null
	 * @param string|null
	 * @param string|null
	 * @return QueryBuilder
	 */
	public function createQueryBuilder(?string $entityClass = NULL, ?string $alias = NULL, ?string $indexBy = NULL) : QueryBuilder
	{
		$qb = new QueryBuilder($this->entityManager);
		if ($entityClass) {
			$qb->from($entityClass, $alias, $indexBy);
			$qb->select($alias);
		}

		return $qb;
	}


	public function createQuery(string $query) : Query
	{
		return $this->entityManager->createQuery($query);
	}


	/**
	 * @return QueryHandlerInterface
	 */
	public function getHandler() : QueryHandlerInterface
	{
		return $this->queryHandler;
	}


	/**
	 * @return EntityManager
	 */
	public function getEntityManager() : EntityManager
	{
		return $this->entityManager;
	}

}
