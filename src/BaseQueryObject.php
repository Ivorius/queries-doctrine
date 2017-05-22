<?php
namespace Librette\Doctrine\Queries;

use Kdyby\StrictObjects\Scream;
use Librette\Queries\InvalidArgumentException;
use Librette\Queries\QueryableInterface;

/**
 * @author David Matejka
 */
abstract class BaseQueryObject implements QueryInterface
{
    use Scream;

	/**
	 * @param QueryableInterface
	 * @return mixed
	 */
	public function fetch(QueryableInterface $queryable)
	{
		if (!$queryable instanceof Queryable) {
			throw new InvalidArgumentException("\$queryable must be an instance of " . Queryable::class);
		}

		return $this->doFetch($queryable);
	}


	/**
	 * @param Queryable
	 * @return mixed result
	 */
	abstract protected function doFetch(Queryable $queryable);

}
