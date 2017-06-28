<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine;

use Doctrine\ORM\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\Query\PairsQuery;
use UselessSoft\Queries\Doctrine\Query\PairsQueryHandler;
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
		$queryHandler->handle($query);

		Assert::true(TRUE);
	}

}


(new PairsQueryTestCase())->run();
