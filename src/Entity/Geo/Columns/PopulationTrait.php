<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait PopulationTrait
{
    /**
     * Численность населения
     *
     * @var int
     *
     * @ORM\Column(type="bigint", options={"default":0})
     */
    protected $population;

    /**
     * @return int
     */
    public function getPopulation(): int
    {
        return (int) $this->population;
    }

    /**
     * @param int $population
     *
     * @return $this
     */
    public function setPopulation(int $population): self
    {
        $this->population = $population;

        return $this;
    }
}
