<?php

declare(strict_types=1);

namespace LibretteTests\Doctrine\Queries\Mocks;

use Doctrine\ORM\EntityManager;
use Kdyby\StrictObjects\Scream;

class EntityManagerMock extends EntityManager
{
    use Scream;

	public function __construct()
	{}

}
