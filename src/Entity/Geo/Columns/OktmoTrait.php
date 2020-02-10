<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait OktmoTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=11, nullable=false)
     */
    protected $oktmo;

    /**
     * @return string
     */
    public function getOktmo(): string
    {
        return $this->oktmo;
    }

    /**
     * @param string $oktmo
     *
     * @return $this
     */
    public function setOktmo(string $oktmo): self
    {
        $this->oktmo = $oktmo;

        return $this;
    }
}
