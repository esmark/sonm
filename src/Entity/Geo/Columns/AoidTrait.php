<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait AoidTrait
{
    /**
     * Уникальный идентификатор записи. Ключевое поле.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=36, nullable=false, unique=true)
     */
    protected $aoid;

    /**
     * @return string
     */
    public function getAoid(): string
    {
        return $this->aoid;
    }

    /**
     * @param string $aoid
     *
     * @return $this
     */
    public function setAoid(string $aoid): self
    {
        $this->aoid = $aoid;

        return $this;
    }
}
