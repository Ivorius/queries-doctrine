<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine;

use Doctrine\DBAL\Logging\DebugStack;
use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\Query\EntityQuery;
use UselessSoft\Queries\Doctrine\Query\EntityQueryHandler;
use UselessSoft\Queries\Doctrine\Queryable;
use UselessSoft\Queries\Doctrine\QueryHandlerInterface;
use UselessSoftTests\Queries\Doctrine\Model\User;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class EntityQueryTestCase extends Tester\TestCase
{
    use EntityManagerTest;
    use Scream;


	public function setUp() : void
	{
	}


	public function testAfterInsert() : void
	{
		$em = $this->createMemoryManager();
		$queryHandler = new EntityQueryHandler(new Queryable($em, \Mockery::mock(QueryHandlerInterface::class)));
		$em->persist($user = new User('John'));
		$em->flush();
		$em->getConnection()->getConfiguration()->setSQLLogger($logger = new DebugStack());
		Assert::same(0, $logger->currentQuery);
		$query = new EntityQuery(User::class, $user->getId());
		Assert::same($user, $queryHandler->handle($query));
		Assert::same(0, $logger->currentQuery);
	}


	public function testRepeatedSelect() : void
	{
		$em = $this->createMemoryManager();
		$queryHandler = new EntityQueryHandler(new Queryable($em, \Mockery::mock(QueryHandlerInterface::class)));
		$em->persist($user = new User('John'));
		$em->flush();
		$em->clear();

		$em->getConnection()->getConfiguration()->setSQLLogger($logger = new DebugStack());
		Assert::same(0, $logger->currentQuery);
		$query = new EntityQuery(User::class, $user->getId());
		Assert::same($user->getId(), $user2 = $queryHandler->handle($query)->getId());
		Assert::same(1, $logger->currentQuery);

		$query = new EntityQuery(User::class, $user->getId());
		Assert::same($user2, $queryHandler->handle($query)->getId());
		Assert::same(1, $logger->currentQuery);
	}

}


(new EntityQueryTestCase())->run();
