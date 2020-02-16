<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait OkatoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=11, nullable=false)
     */
    protected $okato;

    /**
     * @return string
     */
    public function getOkato(): string
    {
        return $this->okato;
    }

    /**
     * @param string $okato
     *
     * @return $this
     */
    public function setOkato(string $okato): self
    {
        $this->okato = trim($okato);

        return $this;
    }
}
