<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Налоговые ставки
 *
 * @ORM\Entity()
 * @ORM\Table(name="tax_rates",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"title"}),
 *      }
 * )
 */
class TaxRate
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\TitleNotBlank;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", length=2, nullable=false)
     * @Assert\Range(min = "0", max = "99")
     */
    protected $percent;

    /**
     * @var Cooperative[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Cooperative", mappedBy="taxRates")
     */
    protected $cooperatives;

    /**
     * TaxRate constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->cooperatives = new ArrayCollection();
        $this->percent      = 0;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle() . ' (' . $this->percent . '%)';
    }

    /**
     * @return int
     */
    public function getPercent(): int
    {
        return $this->percent;
    }

    /**
     * @param int $percent
     *
     * @return $this
     */
    public function setPercent(int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }
}
