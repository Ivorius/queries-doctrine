<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine\Queries;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\QueryObjectHandler;
use UselessSoft\Queries\Doctrine\QueryObjectInterface;
use UselessSoft\Queries\QueryInterface;
use UselessSoftTests\Queries\Doctrine\Model\User;

class UserQueryObjectHandler extends QueryObjectHandler
{
    use Scream;

    protected function createQuery(QueryObjectInterface $queryObject) : QueryBuilder
    {
        assert($queryObject instanceof UsersQueryObject);

        return $this->getQueryable()
            ->createQueryBuilder(User::class, 'u')
            ->where('u.name IN (:names)')
            ->setParameter('names', $queryObject->getNames(), Connection::PARAM_STR_ARRAY);
    }

    public function supports(QueryInterface $query) : bool
    {
        return $query instanceof UsersQueryObject;
    }
}
