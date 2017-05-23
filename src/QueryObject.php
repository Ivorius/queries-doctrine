<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Kdyby\StrictObjects\Scream;

abstract class QueryObject implements QueryObjectInterface
{
    use QueryObjectTrait;
    use Scream;
}
