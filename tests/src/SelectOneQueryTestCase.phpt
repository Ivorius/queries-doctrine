<?php

declare(strict_types=1);

namespace LibretteTests\Doctrine\Queries;

use Doctrine\DBAL\Logging\DebugStack;
use Librette\Doctrine\Queries\EntityQuery;
use Librette\Doctrine\Queries\Queryable;
use Librette\Doctrine\Queries\QueryHandler;
use Librette\Doctrine\Queries\SelectOneQuery;
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
class SelectOneQueryTestCase extends Tester\TestCase
{
	use EntityManagerTest;


	public function setUp() : void
	{
	}


	public function testSelect() : void
	{
		$em = $this->createMemoryManager();
		$queryHandler = new QueryHandler(new Queryable($em, \Mockery::mock(QueryHandlerInterface::class)));
		$em->persist($john = new User('John'));
		$em->persist($jack = new User('Jack'));
		$em->flush();
		$query = new SelectOneQuery(User::class, ['name' => 'John']);
		Assert::same($john, $queryHandler->fetch($query));
		$query = new SelectOneQuery(User::class, ['name' => 'Jack']);
		Assert::same($jack, $queryHandler->fetch($query));
		$query = new SelectOneQuery(User::class, ['name' => 'Jane']);
		Assert::null($queryHandler->fetch($query));
	}

}


(new SelectOneQueryTestCase())->run();
