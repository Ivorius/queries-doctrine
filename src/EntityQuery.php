<?php

declare(strict_types=1);

namespace Librette\Doctrine\Queries;

/**
 * @author David Matejka
 */
class EntityQuery extends BaseQueryObject
{

	/** @var string */
	private $entityName;

	/** @var mixed */
	private $id;


	/**
	 * @param string
	 * @param int|mixed
	 */
	public function __construct(string $entityName, $id)
	{
		$this->entityName = $entityName;
		$this->id = $id;
	}


	protected function doFetch(Queryable $queryable)
	{
		return $queryable->getEntityManager()->find($this->entityName, $this->id);
	}

}
