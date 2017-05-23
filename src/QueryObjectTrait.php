<?php

declare(strict_types=1);

namespace UselessSoft\Queries\Doctrine;

use Doctrine;
use UselessSoft\Queries\Doctrine\Specifications\SpecificationQueryTrait;

trait QueryObjectTrait
{
    use SpecificationQueryTrait;

	/** @var callable[] */
	public $onPostFetch = [];

    /**
     * @return callable[]
     */
    public function getPostFetchListeners() : array
    {
        return $this->onPostFetch;
    }

}
