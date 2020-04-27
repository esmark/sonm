<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Страны
 *
 * @ORM\Entity()
 * @ORM\Table(name="geo_countries",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"engname"}),
 *          @ORM\UniqueConstraint(columns={"name_canonical"}),
 *          @ORM\UniqueConstraint(columns={"offname"}),
 *          @ORM\UniqueConstraint(columns={"iso_code_alpha_2"}),
 *          @ORM\UniqueConstraint(columns={"iso_code_alpha_3"}),
 *          @ORM\UniqueConstraint(columns={"iso_code_numeric"}),
 *      }
 * )
 */
class Country
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\NameCanonicalTrait;
    use Columns\OffnameTrait;
    use Columns\EngnameTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $iso_code_alpha_2;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     */
    protected $iso_code_alpha_3;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint")
     */
    protected $iso_code_numeric;

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

    public function getIsoCodeAlpha2(): string
    {
        return $this->iso_code_alpha_2;
    }

    public function setIsoCodeAlpha2(string $iso_code_alpha_2): self
    {
        $this->iso_code_alpha_2 = $iso_code_alpha_2;

        return $this;
    }

    public function getIsoCodeAlpha3(): string
    {
        return $this->iso_code_alpha_3;
    }

    public function setIsoCodeAlpha3(string $iso_code_alpha_3): self
    {
        $this->iso_code_alpha_3 = $iso_code_alpha_3;

        return $this;
    }

    public function getIsoCodeNumeric(): int
    {
        return $this->iso_code_numeric;
    }

    public function setIsoCodeNumeric(int $iso_code_numeric): self
    {
        $this->iso_code_numeric = $iso_code_numeric;

        return $this;
    }

}
