<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine;

use Doctrine\DBAL\Logging\DebugStack;
use Kdyby\StrictObjects\Scream;
use Tester\Assert;
use Tester\TestCase;
use UselessSoft\Queries\Doctrine\Queryable;
use UselessSoft\Queries\Doctrine\ResultSet;
use UselessSoftTests\Queries\Doctrine\Model\User;
use UselessSoftTests\Queries\Doctrine\Queries\UserQueryObjectHandler;
use UselessSoftTests\Queries\Doctrine\Queries\UsersQueryObject;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class QueryObjectTestCase extends TestCase
{
    use EntityManagerTest;
    use Scream;

    public function tearDown() : void
    {
        \Mockery::close();
    }


    public function testQuery() : void
    {
        $em = $this->createMemoryManager();
        $queryHandler = new UserQueryObjectHandler(new Queryable($em));
        $em->persist($john = new User('John'));
        $em->persist($sam = new User('Sam'));
        $em->persist($janie = new User('Janie'));
        $em->flush();
        $em->getConnection()->getConfiguration()->setSQLLogger($logger = new DebugStack());
        Assert::same(0, $logger->currentQuery);
        $query = new UsersQueryObject(['Janie']);
        $result = $queryHandler->handle($query);
        Assert::type(ResultSet::class, $result);
        Assert::same([$janie], $result->toArray());
        Assert::same(1, $logger->currentQuery);
    }
}


(new QueryObjectTestCase())->run();
