<?php

declare(strict_types=1);

namespace LibretteTests\Doctrine\Queries\Queries;

use Kdyby\StrictObjects\Scream;
use Librette\Doctrine\Queries\Queryable;
use Librette\Doctrine\Queries\QueryHandlerInterface;
use Librette\Queries\QueryInterface;
use LibretteTests\Doctrine\Queries\Model\User;

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
