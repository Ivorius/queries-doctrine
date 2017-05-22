<?php

declare(strict_types=1);

namespace LibretteTests\Doctrine\Queries;

use Librette\Doctrine\Queries\BaseQueryObject;
use Librette\Doctrine\Queries\IQuery;
use Librette\Doctrine\Queries\Queryable;
use Librette\Doctrine\Queries\QueryHandler;
use Librette\Doctrine\Queries\QueryObject;
use Librette\Queries\CountQuery;
use Librette\Queries\QueryInterface as BaseQuery;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Matějka
 * @testCase
 */
class QueryHandlerTestCase extends Tester\TestCase
{

	public function setUp() : void
	{
	}


	public function tearDown() : void
	{
		\Mockery::close();
	}


	public function testSupports() : void
	{
		$queryHandler = new QueryHandler(\Mockery::mock(Queryable::class));
		Assert::true($queryHandler->supports(\Mockery::mock(BaseQueryObject::class)));
		Assert::true($queryHandler->supports(\Mockery::mock(QueryObject::class)));
		Assert::true($queryHandler->supports(\Mockery::mock(IQuery::class)));
		Assert::false($queryHandler->supports(\Mockery::mock(BaseQuery::class)));
		Assert::false($queryHandler->supports(\Mockery::mock(CountQuery::class)));
	}


	public function testFetch() : void
	{
		$queryHandler = new QueryHandler($queryable = \Mockery::mock(Queryable::class));
		$queryHandler->fetch(\Mockery::mock(IQuery::class)->shouldReceive('fetch')->once()->with($queryable)->getMock());
		Assert::true(TRUE);
	}


}


(new QueryHandlerTestCase())->run();
