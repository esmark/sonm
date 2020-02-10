<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Улицы
 *
 * @ORM\Entity()
 * @ORM\Table(name="geo_streets",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"offname"}),
 *          @ORM\Index(columns={"areacode"}),
 *          @ORM\Index(columns={"regioncode"}),
 *          @ORM\Index(columns={"placecode"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"streetcode"}),
 *      }
 * )
 */
class Street
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\AoguidTrait;
    use Columns\AoidTrait;
    use Columns\AreacodeTrait;
    use Columns\CitycodeTrait;
    use Columns\FormalnameTrait;
    use Columns\OffnameTrait;
    use Columns\OkatoTrait;
    use Columns\OktmoTrait;
    use Columns\ShortnameTrait;
    use Columns\RegioncodeTrait;
    use Columns\PlaincodeTrait;
    use Columns\PlacecodeTrait;
    use Columns\StreetcodeTrait;
    use Columns\LatLonTrait;
    use Columns\IfnsflTrait;
    use Columns\IfnsulTrait;
    use Columns\TerrifnsflTrait;
    use Columns\TerrifnsulTrait;

    /**
     * @var City|null
     *
     * @ORM\ManyToOne(targetEntity="City", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $city;

    /**
     * @var Settlement|null
     *
     * @ORM\ManyToOne(targetEntity="Settlement", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $settlement;

    /**
     * @var Province
     *
     * @ORM\ManyToOne(targetEntity="Province", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $province;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $region;

    /**
     * Street constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getOffname() . ' ' . $this->getShortname() . '.';
    }

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     *
     * @return $this
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Settlement|null
     */
    public function getSettlement(): ?Settlement
    {
        return $this->settlement;
    }

    /**
     * @param Settlement|null $settlement
     *
     * @return $this
     */
    public function setSettlement(?Settlement $settlement): self
    {
        $this->settlement = $settlement;

        return $this;
    }

    /**
     * @return Province
     */
    public function getProvince(): Province
    {
        return $this->province;
    }

    /**
     * @param Province $province
     *
     * @return $this
     */
    public function setProvince(Province $province): self
    {
        $this->province = $province;

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
