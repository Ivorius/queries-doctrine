<?php

declare(strict_types=1);

namespace UselessSoftTests\Queries\Doctrine\Model;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Kdyby\StrictObjects\Scream;

/**
 * @ORM\Entity
 */
class User
{
    use Scream;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;


	public function __construct(string $name)
	{
		$this->name = $name;
	}

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

}
