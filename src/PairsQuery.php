<?php

declare(strict_types=1);

namespace Librette\Doctrine\Queries;

use Kdyby\StrictObjects\Scream;

/**
 * @author David Matejka
 */
class PairsQuery implements QueryInterface
{
    use Scream;

	/** @var string */
	private $value;

	/** @var array */
	private $filter = [];

	/** @var array */
	private $orderBy = [];

	/** @var string|null */
	private $key;

	/** @var string */
	private $entityName;


	public function __construct(string $entityName, string $value)
	{
		$this->value = $value;
		$this->entityName = $entityName;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function setFilter(array $filter) : self
	{
		$this->filter = $filter;

		return $this;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function setKey(string $key) : self
	{
		$this->key = $key;

		return $this;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function setOrderBy(array $orderBy) : self
	{
		$this->orderBy = $orderBy;

		return $this;
	}

    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getFilter() : array
    {
        return $this->filter;
    }

    /**
     * @return array
     */
    public function getOrderBy() : array
    {
        return $this->orderBy;
    }

    /**
     * @return string|null
     */
    public function getKey() : ?string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getEntityName() : string
    {
        return $this->entityName;
    }

}
