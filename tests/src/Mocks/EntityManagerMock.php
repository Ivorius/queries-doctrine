<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine\Mocks;

use Doctrine\ORM\EntityManager;
use Kdyby\StrictObjects\Scream;

class EntityManagerMock extends EntityManager
{
    use Scream;

	public function __construct()
	{}

}
