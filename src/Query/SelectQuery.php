<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Query;

use Doctrine\ORM\QueryBuilder;
use UselessSoft\Queries\Doctrine\Queryable;
use UselessSoft\Queries\Doctrine\QueryObject;

class SelectQuery extends QueryObject
{

	/** @var string */
	private $entityClass;

	/** @var array */
	private $filters = [];

	/** @var array */
	private $orderBy = [];

	/** @var string */
	private $indexBy;


	/**
	 * @param string
	 */
	public function __construct(string $entityClass)
	{
		$this->entityClass = $entityClass;
	}


	/**
	 * @param callable
	 * @param string|array|null|mixed
	 * @return self
	 */
	public function filterBy(callable $field, ?$value = NULL) : self
	{
		$this->filters[] = [$field, $value];

		return $this;
	}


	/**
	 * @param string
	 * @param string
	 * @return self
	 */
	public function orderBy(string $field, string $direction = 'ASC') : self
	{
		$this->orderBy[$field] = $direction;

		return $this;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function indexBy(string $field) : self
	{
		if (strpos($field, '.') === FALSE) {
			$field = 'e.' . $field;
		}
		$this->indexBy = $field;

		return $this;
	}


	protected function createQuery(Queryable $queryable) : QueryBuilder
	{
		$qb = $queryable->createQueryBuilder($this->entityClass, 'e');
		foreach ($this->filters as $filter) {
			list ($field, $value) = $filter;
			if ($value === NULL && $field instanceof \Closure) {
				$field($qb, 'e');
			} else {
				$qb->whereCriteria([$field => $value]);
			}
		}
		foreach ($this->orderBy as $field => $direction) {
			$qb->autoJoinOrderBy($field, $direction);
		}

		return $qb;
	}

}
