<?php

declare(strict_types=1);

namespace App\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Краткое наименование типа объекта
 *
 * @ORM\Entity()
 * @ORM\Table(name="geo_abbreviations",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *     },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"level", "shortname"}),
 *      }
 * )
 */
class Abbreviation
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    use Columns\FullnameTrait;

    /**
     * Ключевое поле
     *
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false, unique=true)
     */
    protected $code;

    /**
     * Ключевое поле
     *
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $level;

    /**
     * Краткое наименование типа объекта
     *
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    protected $shortname;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function __toString(): string
    {
        return $this->getFullname();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getShortname(): string
    {
        return $this->shortname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = trim($shortname);

        return $this;
    }
}
