<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

use App\Entity\Geo\Abbreviation;

trait FullnameTrait
{
    /**
     * Полное наименование типа объекта
     *
     * @var string
     *
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    protected $fullname;

    /**
     * @return string
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     *
     * @return $this
     */
    public function setFullname(string $fullname): self
    {
        $this->fullname = trim($fullname);

        return $this;
    }
}
