<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait IfnsflTrait
{
    /**
     * Код ИФНС ФЛ
     *
     * @var string
     *
     * @ORM\Column(type="string", length=4, nullable=false)
     */
    protected $ifnsfl;

    /**
     * @return string
     */
    public function getIfnsfl(): string
    {
        return $this->ifnsfl;
    }

    /**
     * @param string $ifnsfl
     *
     * @return $this
     */
    public function setIfnsfl(string $ifnsfl): self
    {
        $this->ifnsfl = $ifnsfl;

        return $this;
    }
}
