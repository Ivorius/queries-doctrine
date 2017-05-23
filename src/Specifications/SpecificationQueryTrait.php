<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine\Specifications;

trait SpecificationQueryTrait
{

	/** @var SpecificationInterface[] */
	private $specifications = [];


	public function addSpecification(SpecificationInterface $specification) : void
	{
		$this->specifications[] = $specification;
	}

    /**
     * @return SpecificationInterface[]
     */
    public function getSpecifications() : array
    {
        return $this->specifications;
    }

}
