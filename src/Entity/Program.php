<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Целевые программы
 *
 * @ORM\Entity()
 * @ORM\Table(name="programs",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"title"}),
 *      },
 * )
 */
class Program
{
    use ColumnTrait\Id;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\Description;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\UserNotNull;

    /**
     * @var Cooperative[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Cooperative", mappedBy="programs")
     */
    protected $cooperatives;

    /**
     * Program constructor.
     */
    public function __construct()
    {
        $this->cooperatives = new ArrayCollection();
        $this->created_at   = new \DateTime();
        $this->is_enabled   = true;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }

    /**
     * @return Cooperative[]|ArrayCollection
     */
    public function getCooperatives()
    {
        return $this->cooperatives;
    }

    /**
     * @param Cooperative[]|ArrayCollection $cooperatives
     *
     * @return $this
     */
    public function setCooperatives($cooperatives): self
    {
        $this->cooperatives = $cooperatives;

        return $this;
    }
}
