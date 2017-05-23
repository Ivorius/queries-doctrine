<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine;

use Doctrine\DBAL\Logging\DebugStack;
use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\Query\SelectOneQuery;
use UselessSoft\Queries\Doctrine\Query\SelectOneQueryHandler;
use UselessSoft\Queries\Doctrine\Queryable;
use UselessSoft\Queries\Doctrine\QueryHandlerInterface;
use UselessSoftTests\Queries\Doctrine\Model\User;
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
	use Scream;


	public function setUp() : void
	{
	}


	public function testSelect() : void
	{
		$em = $this->createMemoryManager();
		$queryHandler = new SelectOneQueryHandler(new Queryable($em, \Mockery::mock(QueryHandlerInterface::class)));
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
