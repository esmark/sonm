<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait IfnsulTrait
{
    /**
     * Код ИФНС ЮЛ
     *
     * @var string
     *
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    protected $ifnsul;

    /**
     * @return string
     */
    public function getIfnsul(): string
    {
        return $this->ifnsul;
    }

    /**
     * @param string $ifnsul
     *
     * @return $this
     */
    public function setIfnsul(string $ifnsul): self
    {
        $this->ifnsul = $ifnsul;

        return $this;
    }
}
