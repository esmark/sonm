<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Страны
 *
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\Table(name="geo_countries",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"engname"}),
 *          @ORM\Index(columns={"offname"}),
 *      }
 * )
 */
class Country
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\CodeTrait;
    use Columns\NameCanonicalTrait;
    use Columns\OffnameTrait;
    use Columns\EngnameTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $isoCodeAlpha2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     */
    protected $isoCodeAlpha3;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     */
    protected $isoCodeNumeric;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at  = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getOffname();
    }

    /**
     * @return string
     */
    public function getIsoCodeAlpha2(): string
    {
        return $this->isoCodeAlpha2;
    }

    /**
     * @param string $isoCodeAlpha2
     *
     * @return $this
     */
    public function setIsoCodeAlpha2(string $isoCodeAlpha2): self
    {
        $this->isoCodeAlpha2 = $isoCodeAlpha2;

        return $this;
    }

    /**
     * @return string
     */
    public function getIsoCodeAlpha3(): string
    {
        return $this->isoCodeAlpha3;
    }

    /**
     * @param string $isoCodeAlpha3
     *
     * @return $this
     */
    public function setIsoCodeAlpha3(string $isoCodeAlpha3): self
    {
        $this->isoCodeAlpha3 = $isoCodeAlpha3;

        return $this;
    }

    /**
     * @return int
     */
    public function getIsoCodeNumeric(): int
    {
        return $this->isoCodeNumeric;
    }

    /**
     * @param int $isoCodeNumeric
     *
     * @return $this
     */
    public function setIsoCodeNumeric(int $isoCodeNumeric): self
    {
        $this->isoCodeNumeric = $isoCodeNumeric;

        return $this;
    }
}
