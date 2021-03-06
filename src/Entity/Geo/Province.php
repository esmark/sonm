<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Районы, области, провинции, (area)
 *
 * @ORM\Entity()
 * @ORM\Table(name="geo_provinces",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"offname"}),
 *          @ORM\Index(columns={"regioncode"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"regioncode", "areacode"}),
 *      }
 * )
 */
class Province
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\AoguidTrait;
    use Columns\AoidTrait;
    use Columns\AreacodeTrait;
    use Columns\FormalnameTrait;
    use Columns\EngnameTrait;
    use Columns\OffnameTrait;
    use Columns\OkatoTrait;
    use Columns\NameCanonicalTrait;
    use Columns\ShortnameTrait;
    use Columns\RegioncodeTrait;
    use Columns\IfnsflTrait;
    use Columns\IfnsulTrait;
    use Columns\TerrifnsflTrait;
    use Columns\TerrifnsulTrait;
    use Columns\PlaincodeTrait;

    /**
     * @var Abbreviation
     *
     * @ORM\ManyToOne(targetEntity="Abbreviation", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $abbreviation;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="provinces", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $region;

    /**
     * @var City[]|Collection
     *
     * @ORM\OneToMany(targetEntity="City", mappedBy="province", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $cities;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->cities     = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getOffname() . ' ' . $this->getShortname() . '.';
    }

    public function getAbbreviation(): Abbreviation
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(Abbreviation $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * @return City[]|Collection
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * @param City[]|Collection $cities
     *
     * @return $this
     */
    public function setCities($cities): self
    {
        $this->cities = $cities;

        return $this;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
