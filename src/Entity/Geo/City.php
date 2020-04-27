<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Города
 *
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\Table(name="geo_cities",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"areacode"}),
 *          @ORM\Index(columns={"centstatus"}),
 *          @ORM\Index(columns={"engname"}),
 *          @ORM\Index(columns={"name_canonical"}),
 *          @ORM\Index(columns={"offname"}),
 *          @ORM\Index(columns={"population"}),
 *          @ORM\Index(columns={"regioncode"}),
 *          @ORM\Index(columns={"iso_code"}),
 *      }
 * )
 */
class City
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\AoguidTrait;
    use Columns\AoidTrait;
    use Columns\AreacodeTrait;
    use Columns\CentstatusTrait;
    use Columns\CitycodeTrait;
    use Columns\FormalnameTrait;
    use Columns\EngnameTrait;
    use Columns\OffnameTrait;
    use Columns\OkatoTrait;
    use Columns\OktmoTrait;
    use Columns\NameCanonicalTrait;
    use Columns\ShortnameTrait;
    use Columns\RegioncodeTrait;
    use Columns\PlaincodeTrait;
    use Columns\PopulationTrait;
    use Columns\PostalcodeTrait;
    use Columns\TimezoneTrait;
    use Columns\IsoCodeTrait;
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
     * @var Province
     *
     * @ORM\ManyToOne(targetEntity="Province", inversedBy="cities", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $province;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="cities", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $region;

    /**
     * @var Settlement[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Settlement", mappedBy="city", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $settlements;

    /**
     * City constructor.
     */
    public function __construct()
    {
        $this->created_at  = new \DateTime();
        $this->population  = 0;
        $this->settlements = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getShortname() . '. ' . $this->getOffname();
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

    /**
     * @return Settlement[]|Collection
     */
    public function getSettlements(): Collection
    {
        return $this->settlements;
    }
}
