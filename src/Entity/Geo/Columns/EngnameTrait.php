<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait EngnameTrait
{
    /**
     * Название на енг
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    protected $engname;

    /**
     * @return string
     */
    public function getEngname(): string
    {
        return $this->engname;
    }

    /**
     * @param string $engname
     *
     * @return $this
     */
    public function setEngname(string $engname): self
    {
        $this->engname = $engname;

        return $this;
    }
}
