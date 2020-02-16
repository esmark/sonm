<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Населенные пункты
 *
 * @ORM\Entity()
 * @ORM\Table(name="geo_settlements",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"offname"}),
 *          @ORM\Index(columns={"centstatus"}),
 *          @ORM\Index(columns={"areacode"}),
 *          @ORM\Index(columns={"regioncode"}),
 *          @ORM\Index(columns={"placecode"}),
 *      }
 * )
 */
class Settlement
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\AoguidTrait;
    use Columns\AoidTrait;
    use Columns\AreacodeTrait;
    use Columns\CentstatusTrait;
    use Columns\CitycodeTrait;
    use Columns\FormalnameTrait;
    use Columns\OffnameTrait;
    use Columns\OkatoTrait;
    use Columns\OktmoTrait;
    use Columns\NameCanonicalTrait;
    use Columns\ShortnameTrait;
    use Columns\RegioncodeTrait;
    use Columns\PlaincodeTrait;
    use Columns\PlacecodeTrait;
    use Columns\PostalcodeTrait;
    use Columns\TimezoneTrait;
    use Columns\LatLonTrait;
    use Columns\IfnsflTrait;
    use Columns\IfnsulTrait;
    use Columns\TerrifnsflTrait;
    use Columns\TerrifnsulTrait;

    /**
     * @var Abbreviation
     *
     * @ORM\ManyToOne(targetEntity="Abbreviation", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $abbreviation;

    /**
     * @var City|null
     *
     * @ORM\ManyToOne(targetEntity="City", inversedBy="settlements", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $city;

    /**
     * @var Province|null
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
     * City constructor.
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
     * @return Abbreviation
     */
    public function getAbbreviation(): Abbreviation
    {
        return $this->abbreviation;
    }

    /**
     * @param Abbreviation $abbreviation
     *
     * @return $this
     */
    public function setAbbreviation(Abbreviation $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
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
     * @return Province|null
     */
    public function getProvince(): ?Province
    {
        return $this->province;
    }

    /**
     * @param Province|null $province
     *
     * @return $this
     */
    public function setProvince(?Province $province): self
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
