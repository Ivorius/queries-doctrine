<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Specifications;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

trait SpecificationQueryHandlerTrait
{

    /**
     * @param SpecificationInterface[] $specifications
     */
	protected function applySpecifications(array $specifications, QueryBuilder $queryBuilder, string $alias)
	{
		$andX = new Query\Expr\Andx();
		foreach ($specifications as $specification) {
			array_map([$andX, 'add'], array_filter((array) $specification->match($queryBuilder, $alias)));
		}
		if ($andX->count() > 0) {
			$queryBuilder->andWhere($andX);
		}
	}


    /**
     * @param SpecificationInterface[] $specifications
     */
	protected function modifyQuery(array $specifications, Query $query) : void
	{
		foreach ($specifications as $specification) {
			$specification->modifyQuery($query);
		}
	}

}
