<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait FormalnameTrait
{
    /**
     * Формализованное наименование
     *
     * @var string
     *
     * @ORM\Column(type="string", length=120, nullable=false)
     */
    protected $formalname;

    /**
     * @return string
     */
    public function getFormalname(): string
    {
        return $this->formalname;
    }

    /**
     * @param string $formalname
     *
     * @return $this
     */
    public function setFormalname(string $formalname): self
    {
        $this->formalname = trim($formalname);

        return $this;
    }
}
