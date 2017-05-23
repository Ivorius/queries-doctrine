<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use UselessSoft\Queries\Doctrine\Specifications\SpecificationAwareInterface;

interface QueryObjectInterface extends QueryInterface, SpecificationAwareInterface
{
    /**
     * @return callable[]
     */
    public function getPostFetchListeners() : array;
}
