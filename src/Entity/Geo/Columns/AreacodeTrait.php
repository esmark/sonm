<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait AreacodeTrait
{
    /**
     * Код района
     *
     * @var string
     *
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    protected $areacode;

    /**
     * @return string
     */
    public function getAreacode(): string
    {
        return $this->areacode;
    }

    /**
     * @param string $areacode
     *
     * @return $this
     */
    public function setAreacode(string $areacode): self
    {
        $this->areacode = $areacode;

        return $this;
    }
}
