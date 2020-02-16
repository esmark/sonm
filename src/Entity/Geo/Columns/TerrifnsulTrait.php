<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait TerrifnsulTrait
{
    /**
     * Код территориального участка ИФНС ЮЛ
     *
     * @var string
     *
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    protected $terrifnsul;

    /**
     * @return string
     */
    public function getTerrifnsul(): string
    {
        return $this->terrifnsul;
    }

    /**
     * @param string $terrifnsul
     *
     * @return $this
     */
    public function setTerrifnsul(string $terrifnsul): self
    {
        $this->terrifnsul = trim($terrifnsul);

        return $this;
    }
}
