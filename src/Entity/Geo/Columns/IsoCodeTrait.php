<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait IsoCodeTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    protected $isoCode;

    /**
     * @return string|null
     */
    public function getIsoCode(): ?string
    {
        return $this->isoCode;
    }

    /**
     * @param string|null $isoCode
     *
     * @return $this
     */
    public function setIsoCode(?string $isoCode): self
    {
        $this->isoCode = trim($isoCode);

        return $this;
    }
}
