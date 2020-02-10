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
    use Columns\ShortnameTrait;
    use Columns\RegioncodeTrait;
    use Columns\IfnsflTrait;
    use Columns\IfnsulTrait;
    use Columns\TerrifnsflTrait;
    use Columns\TerrifnsulTrait;
    use Columns\PlaincodeTrait;

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

    /**
     * Province constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->cities     = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getOffname() . ' ' . $this->getShortname() . '.';
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

    /**
     * @return Region
     */
    public function getRegion(): Region
    {
        return $this->region;
    }

    /**
     * @param Region $region
     *
     * @return $this
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
