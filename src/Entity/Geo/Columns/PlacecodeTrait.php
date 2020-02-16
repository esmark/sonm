<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait PlacecodeTrait
{
    /**
     * Код населенного пункта
     *
     * @var string
     *
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    protected $placecode;

    /**
     * @return string
     */
    public function getPlacecode(): string
    {
        return $this->placecode;
    }

    /**
     * @param string $placecode
     *
     * @return $this
     */
    public function setPlacecode(string $placecode): self
    {
        $this->placecode = trim($placecode);

        return $this;
    }
}
