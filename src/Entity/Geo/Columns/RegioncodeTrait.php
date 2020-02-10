<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait RegioncodeTrait
{
    /**
     * Код региона
     *
     * @var string
     *
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    protected $regioncode;

    /**
     * @return string
     */
    public function getRegioncode(): string
    {
        return $this->regioncode;
    }

    /**
     * @param string $regioncode
     *
     * @return $this
     */
    public function setRegioncode(string $regioncode): self
    {
        $this->regioncode = $regioncode;

        return $this;
    }
}
