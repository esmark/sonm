<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="geo_regions",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"iso_code"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"regioncode"}),
 *          @ORM\UniqueConstraint(columns={"okato"}),
 *      }
 * )
 */
class Region
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\AoguidTrait;
    use Columns\AoidTrait;
    use Columns\RegioncodeTrait;
    use Columns\FormalnameTrait;
    use Columns\EngnameTrait;
    use Columns\IfnsflTrait;
    use Columns\IfnsulTrait;
    use Columns\OffnameTrait;
    use Columns\OkatoTrait;
    use Columns\ShortnameTrait;
    use Columns\PlaincodeTrait;
    use Columns\IsoCodeTrait;

    /**
     * @var Province[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Province", mappedBy="region", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $provinces;

    /**
     * @var City[]|Collection
     *
     * @ORM\OneToMany(targetEntity="City", mappedBy="region", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $cities;

    /**
     * Region constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->cities     = new ArrayCollection();
        $this->provinces  = new ArrayCollection();
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
     * @return Province[]|Collection
     */
    public function getProvinces()
    {
        return $this->provinces;
    }

    /**
     * @param Province[]|Collection $provinces
     *
     * @return $this
     */
    public function setProvinces($provinces): self
    {
        $this->provinces = $provinces;

        return $this;
    }
}
