<?php

declare(strict_types=1);

namespace LibretteTests\Doctrine\Queries;

use Doctrine\DBAL\Logging\DebugStack;
use Kdyby\StrictObjects\Scream;
use Librette\Doctrine\Queries\EntityQuery;
use Librette\Doctrine\Queries\Queryable;
use Librette\Doctrine\Queries\QueryHandler;
use Librette\Queries\QueryHandlerInterface;
use LibretteTests\Doctrine\Queries\Model\User;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
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
		$queryHandler = new QueryHandler(new Queryable($em, \Mockery::mock(QueryHandlerInterface::class)));
		$em->persist($user = new User('John'));
		$em->flush();
		$em->getConnection()->getConfiguration()->setSQLLogger($logger = new DebugStack());
		Assert::same(0, $logger->currentQuery);
		$query = new EntityQuery(User::class, $user->getId());
		Assert::same($user, $queryHandler->fetch($query));
		Assert::same(0, $logger->currentQuery);
	}


	public function testRepeatedSelect() : void
	{
		$em = $this->createMemoryManager();
		$queryHandler = new QueryHandler(new Queryable($em, \Mockery::mock(QueryHandlerInterface::class)));
		$em->persist($user = new User('John'));
		$em->flush();
		$em->clear();

		$em->getConnection()->getConfiguration()->setSQLLogger($logger = new DebugStack());
		Assert::same(0, $logger->currentQuery);
		$query = new EntityQuery(User::class, $user->getId());
		Assert::same($user->getId(), $user2 = $queryHandler->fetch($query)->getId());
		Assert::same(1, $logger->currentQuery);

		$query = new EntityQuery(User::class, $user->getId());
		Assert::same($user2, $queryHandler->fetch($query)->getId());
		Assert::same(1, $logger->currentQuery);
	}

}


(new EntityQueryTestCase())->run();
