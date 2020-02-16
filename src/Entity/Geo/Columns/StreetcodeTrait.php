<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait StreetcodeTrait
{
    /**
     * Код улицы
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    protected $streetcode;

    /**
     * @return string|null
     */
    public function getStreetcode(): ?string
    {
        return $this->streetcode;
    }

    /**
     * @param string|null $streetcode
     *
     * @return $this
     */
    public function setStreetcode(?string $streetcode): self
    {
        $this->streetcode = trim($streetcode);

        return $this;
    }
}
