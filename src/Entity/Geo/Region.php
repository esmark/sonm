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
 *          @ORM\UniqueConstraint(columns={"fullname_canonical"}),
 *          @ORM\UniqueConstraint(columns={"okato"}),
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
    use Columns\FullnameTrait;
    use Columns\ShortnameTrait;
    use Columns\PlaincodeTrait;
    use Columns\IsoCodeTrait;

    /**
     * Полное наименование типа объекта (в нижнем регистре - для поиска)
     *
     * @var string
     *
     * @ORM\Column(type="string", length=120, nullable=false)
     */
    protected $fullname_canonical;

    /**
     * @var Abbreviation
     *
     * @ORM\ManyToOne(targetEntity="Abbreviation", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $abbreviation;

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

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->cities     = new ArrayCollection();
        $this->provinces  = new ArrayCollection();
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
    public function setCities(Collection $cities): self
    {
        $this->cities = $cities;

        return $this;
    }

    public function getFullnameCanonical(): string
    {
        return $this->fullname_canonical;
    }

    public function setFullnameCanonical(string $fullname_canonical): self
    {
        $this->fullname_canonical = trim($fullname_canonical);

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
