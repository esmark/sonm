<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait CitycodeTrait
{
    /**
     * Код города
     *
     * @var string
     *
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    protected $citycode;

    /**
     * @return string
     */
    public function getCitycode(): string
    {
        return $this->citycode;
    }

    /**
     * @param string $citycode
     *
     * @return $this
     */
    public function setCitycode(string $citycode): self
    {
        $this->citycode = trim($citycode);

        return $this;
    }
}
