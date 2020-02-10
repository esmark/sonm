<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait PostalcodeTrait
{
    /**
     * Почтовый индекс
     *
     * @var string
     *
     * @ORM\Column(type="string", length=6, nullable=false, unique=true)
     */
    protected $postalcode;

    /**
     * @return string
     */
    public function getPostalcode(): string
    {
        return $this->postalcode;
    }

    /**
     * @param string $postalcode
     *
     * @return $this
     */
    public function setPostalcode(string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }
}
