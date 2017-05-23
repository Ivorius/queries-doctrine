<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Specifications;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

interface SpecificationInterface
{

	/**
	 * @param QueryBuilder
	 * @param string
	 * @return void|string|array|mixed
	 */
	public function match(QueryBuilder $queryBuilder, string $alias);


	/**
	 * @param Query
	 * @return void
	 */
	public function modifyQuery(Query $query);

}
