<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Specifications;

interface SpecificationAwareInterface
{
    public function addSpecification(SpecificationInterface $specification) : void;

    /**
     * @return SpecificationInterface[]
     */
    public function getSpecifications() : array;
}
