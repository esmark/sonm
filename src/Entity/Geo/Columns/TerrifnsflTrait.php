<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait TerrifnsflTrait
{
    /**
     * Код территориального участка ИФНС ФЛ
     *
     * @var string
     *
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    protected $terrifnsfl;

    /**
     * @return string
     */
    public function getTerrifnsfl(): string
    {
        return $this->terrifnsfl;
    }

    /**
     * @param string $terrifnsfl
     *
     * @return $this
     */
    public function setTerrifnsfl(string $terrifnsfl): self
    {
        $this->terrifnsfl = $terrifnsfl;

        return $this;
    }
}
