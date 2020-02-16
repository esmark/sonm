<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait NameCanonicalTrait
{
    /**
     * Название в нижнем регистре - для поиска
     *
     * @var string
     *
     * @ORM\Column(type="string", length=120, nullable=false)
     */
    protected $name_canonical;

    /**
     * @return string
     */
    public function getNameCanonical(): string
    {
        return $this->name_canonical;
    }

    /**
     * @param string $name_canonical
     *
     * @return $this
     */
    public function setNameCanonical(string $name_canonical): self
    {
        $this->name_canonical = trim($name_canonical);

        return $this;
    }
}
