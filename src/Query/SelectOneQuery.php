<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Query;

use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\QueryInterface;

/**
 * @author David Matejka
 */
class SelectOneQuery implements QueryInterface
{
    use Scream;

	/** @var string */
	private $entityClass;

	/** @var array */
	private $filters = [];

	/** @var array */
	private $orderBy = [];

	/**
	 * @param string
	 */
	public function __construct(string $entityClass, array $filters = [])
	{
		$this->entityClass = $entityClass;
		$this->filters = array_map(NULL, array_keys($filters), $filters);
	}


	/**
	 * @param string|\Closure
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

}
