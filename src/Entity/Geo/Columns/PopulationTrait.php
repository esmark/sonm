<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait PopulationTrait
{
    /**
     * Численность населения
     *
     * @var int|null
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $population;

    /**
     * @return int|null
     */
    public function getPopulation(): ?int
    {
        if (empty($this->population)) {
            return null;
        }

        return (int) $this->population;
    }

    /**
     * @param int|null $population
     *
     * @return $this
     */
    public function setPopulation(?int $population): self
    {
        $this->population = $population;

        return $this;
    }
}
