<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Kdyby\StrictObjects\Scream;

class Queryable implements QueryableInterface
{
    use Scream;

	/** @var EntityManager */
	protected $entityManager;


	/**
	 * @param EntityManager
	 * @param QueryHandlerInterface
	 */
	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
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
	 * @return EntityManager
	 */
	public function getEntityManager() : EntityManager
	{
		return $this->entityManager;
	}

}
