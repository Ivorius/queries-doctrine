<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine\Queries;

use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\Queryable;
use UselessSoft\Queries\Doctrine\QueryHandlerInterface;
use UselessSoft\Queries\QueryInterface;
use UselessSoftTests\Queries\Doctrine\Model\User;

class UserCountQueryHandler implements QueryHandlerInterface
{
    use Scream;

    /** @var Queryable */
    private $queryable;

    public function __construct(Queryable $queryable)
    {
        $this->queryable = $queryable;
    }

    public function fetch(QueryInterface $queryable) : int
    {
        return (int) $this->queryable->createQueryBuilder(User::class, 'u')
            ->select('COUNT(u.id) AS c')
            ->getQuery()->getSingleScalarResult();
    }

    public function supports(QueryInterface $query) : bool
    {
        return $query instanceof UserCountQuery;
    }
}
