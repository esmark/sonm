<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait ShortnameTrait
{
    /**
     * Краткое наименование типа объекта
     *
     * @var string
     *
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    protected $shortname;

    /**
     * @return string
     */
    public function getShortname(): string
    {
        return $this->shortname;
    }

    /**
     * @param string $shortname
     *
     * @return $this
     */
    public function setShortname(string $shortname): self
    {
        $this->shortname = trim($shortname);

        return $this;
    }
}
