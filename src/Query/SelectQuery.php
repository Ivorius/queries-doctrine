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
	public function filterBy(callable $field, $value = NULL) : self
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

	/**
	 * @return string
	 */
	public function getEntityClass() : string
	{
		return $this->entityClass;
	}

	/**
	 * @return array
	 */
	public function getFilters() : array
	{
		return $this->filters;
	}

	/**
	 * @return array
	 */
	public function getOrderBy() : array
	{
		return $this->orderBy;
	}

	/**
	 * @return string
	 */
	public function getIndexBy() : string
	{
		return $this->indexBy;
	}

}
