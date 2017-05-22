<?php

declare(strict_types=1);

namespace LibretteTests\Doctrine\Queries;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Kdyby\StrictObjects\Scream;
use Librette\Doctrine\Queries\PairsQuery;
use Librette\Doctrine\Queries\PairsQueryHandler;
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
class PairsQueryTestCase extends Tester\TestCase
{
    use Scream;

	public function tearDown() : void
	{
		\Mockery::close();
	}


	public function testBasic() : void
	{
		$repo = \Mockery::mock(EntityRepository::class)
			->shouldReceive('findPairs')
			->once()
			->withArgs([
				['name' => 'John'],
				'name',
				['name' => 'ASC'],
				'id'
			])
			->getMock();
		$em = \Mockery::mock(EntityManager::class)
			->shouldReceive('getRepository')
			->andReturn($repo)
			->getMock();
		$queryHandler = new PairsQueryHandler(new Queryable($em, \Mockery::mock(QueryHandlerInterface::class)));
		$query = new PairsQuery(User::class, 'name');
		$query->setKey('id');
		$query->setFilter(['name' => 'John']);
		$query->setOrderBy(['name' => 'ASC']);
		$queryHandler->fetch($query);

		Assert::true(TRUE);
	}

}


(new PairsQueryTestCase())->run();
