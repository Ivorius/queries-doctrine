<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Doctrine\ORM;
use Doctrine\ORM\Tools\Pagination\Paginator as ResultPaginator;
use Kdyby\Doctrine\NativeQueryWrapper;
use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Exception\InvalidStateException;
use UselessSoft\Queries\ResultSetInterface;


/**
 * @author David Matejka
 */
class ResultSet implements \IteratorAggregate, ResultSetInterface
{
    use Scream;

	/** @var int */
	private $totalCount;

	/** @var \Doctrine\ORM\Query */
	private $query;

	/** @var QueryObject */
	private $queryObject;

	/** @var Queryable */
	private $repository;

	/** @var bool */
	private $fetchJoinCollection = TRUE;

	/** @var bool|null */
	private $useOutputWalkers;

	/** @var \Iterator */
	private $iterator;

	/** @var bool */
	private $frozen = FALSE;


	/**
	 * @param \Doctrine\ORM\AbstractQuery
	 * @param QueryObject
	 * @param Queryable
	 */
	public function __construct(ORM\AbstractQuery $query, ?QueryObject $queryObject = NULL, ?Queryable $repository = NULL)
	{
		$this->query = $query;
		$this->queryObject = $queryObject;
		$this->repository = $repository;
		if ($this->query instanceof NativeQueryWrapper || $this->query instanceof ORM\NativeQuery) {
			$this->fetchJoinCollection = FALSE;
		}
	}


	/**
	 * @param bool
	 * @throws InvalidStateException
	 * @return ResultSet
	 */
	public function setFetchJoinCollection(bool $fetchJoinCollection) : self
	{
		$this->updating();

		$this->fetchJoinCollection = (bool) $fetchJoinCollection;
		$this->iterator = NULL;

		return $this;
	}


	/**
	 * @param bool|null
	 * @throws InvalidStateException
	 * @return ResultSet
	 */
	public function setUseOutputWalkers(?bool $useOutputWalkers) : self
	{
		$this->updating();

		$this->useOutputWalkers = $useOutputWalkers;
		$this->iterator = NULL;

		return $this;
	}


	/**
	 * @param int
	 * @param int
	 * @return ResultSet
	 */
	public function applyPaging(int $offset, int $limit) : ResultSetInterface
	{
		if ($this->query->getFirstResult() != $offset || $this->query->getMaxResults() != $limit) {
			$this->query->setFirstResult($offset);
			$this->query->setMaxResults($limit);
			$this->iterator = NULL;
		}

		return $this;
	}


	/**
	 * @return bool
	 */
	public function isEmpty() : bool
	{
		$count = $this->getTotalCount();
		$offset = $this->query->getFirstResult();

		return $count <= $offset;
	}


	/**
	 * @throws ORM\Query\QueryException
	 * @return int
	 */
	public function getTotalCount() : int
	{
		if ($this->totalCount === NULL) {
			$this->frozen = TRUE;
			$paginatedQuery = $this->createPaginatedQuery($this->query);
			$this->totalCount = $paginatedQuery->count();
		}

		return $this->totalCount;
	}


	/**
	 * @param int|null
	 * @return \ArrayIterator
	 */
	public function getIterator(?int $hydrationMode = NULL) : iterable
	{
		if ($this->iterator !== NULL) {
			return $this->iterator;
		}
		if ($hydrationMode !== NULL) {
			$this->query->setHydrationMode($hydrationMode);
		}
		$this->frozen = TRUE;
		if ($this->fetchJoinCollection && ($this->query->getMaxResults() > 0 || $this->query->getFirstResult() > 0)) {
			$this->iterator = $this->createPaginatedQuery($this->query)->getIterator();
		} else {
			$this->iterator = new \ArrayIterator($this->query->getResult(NULL));
		}
		if ($this->repository && $this->queryObject) {
			$this->queryObject->queryFetched($this->repository, $this->iterator);
		}

		return $this->iterator;
	}


	/**
	 * @param int|null
	 * @return array
	 */
	public function toArray(?int $hydrationMode = NULL) : array
	{
		return iterator_to_array(clone $this->getIterator($hydrationMode), TRUE);
	}


	/**
	 * @return int
	 */
	public function count() : int
	{
		return $this->getIterator()->count();
	}


	/**
	 * @param ORM\Query
	 * @return ResultPaginator
	 */
	private function createPaginatedQuery(ORM\Query $query) : ResultPaginator
	{
		$paginated = new ResultPaginator($query, $this->fetchJoinCollection);
		$paginated->setUseOutputWalkers($this->useOutputWalkers);

		return $paginated;
	}


	private function updating() : void
	{
		if ($this->frozen !== FALSE) {
			throw new InvalidStateException("Cannot modify result set, that was already fetched from storage.");
		}
	}

}
