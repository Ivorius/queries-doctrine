<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Kdyby\StrictObjects\Scream;

abstract class QueryObjectHandler implements QueryHandlerInterface
{
    use QueryObjectHandlerTrait;
    use Scream;

    /** @var QueryableInterface */
    private $queryable;

    public function __construct(QueryableInterface $queryable)
    {
        $this->queryable = $queryable;
    }

    protected function getQueryable() : QueryableInterface
    {
        return $this->queryable;
    }
}
