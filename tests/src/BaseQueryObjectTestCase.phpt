<?php

declare(strict_types=1);

namespace LibretteTests\Doctrine\Queries;

use Kdyby\StrictObjects\Scream;
use Librette\Doctrine\Queries\Queryable;
use Librette\Doctrine\Queries\QueryHandler;
use Librette\Queries\Internal\InternalQueryable;
use Librette\Queries\InvalidArgumentException;
use Librette\Queries\QueryHandlerChain;
use Librette\Queries\QueryHandlerInterface;
use LibretteTests\Doctrine\Queries\Model\User;
use LibretteTests\Doctrine\Queries\Queries\UserCountQuery;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 * @testCase
 */
class BaseQueryObjectTestCase extends Tester\TestCase
{
	use EntityManagerTest;
	use Scream;


	public function tearDown()
	{
		\Mockery::close();
	}


	public function testBasic()
	{
		$em = $this->createMemoryManager();
		$queryHandler = new QueryHandlerChain();
		$queryHandler->addHandler(new QueryHandler(new Queryable($em, $queryHandler)));
		$em->persist(new User('John'));
		$em->persist(new User('Jack'));
		$em->flush();
		Assert::same(2, $queryHandler->fetch(new UserCountQuery()));
	}


	public function testInvalidQueryable()
	{
		Assert::throws(function () {
			(new UserCountQuery())->fetch(new InternalQueryable(\Mockery::mock(QueryHandlerInterface::class)));
		}, InvalidArgumentException::class);
	}

}


(new BaseQueryObjectTestCase())->run();
