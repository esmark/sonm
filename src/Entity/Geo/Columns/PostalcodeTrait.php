<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait PostalcodeTrait
{
    /**
     * Почтовый индекс
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    protected $postalcode;

    /**
     * @return string|null
     */
    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    /**
     * @param string|null $postalcode
     *
     * @return $this
     */
    public function setPostalcode(?string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }
}
