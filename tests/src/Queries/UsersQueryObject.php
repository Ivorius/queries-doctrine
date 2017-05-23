<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine\Queries;

use Kdyby\StrictObjects\Scream;
use UselessSoft\Queries\Doctrine\QueryObject;

class UsersQueryObject extends QueryObject
{
    use Scream;

    /** @var string[] */
    private $names;

    public function __construct(array $names)
    {
        $this->names = $names;
    }

    /**
     * @return string[]
     */
    public function getNames() : array
    {
        return $this->names;
    }

}
