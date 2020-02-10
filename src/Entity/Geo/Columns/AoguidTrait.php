<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait AoguidTrait
{
    /**
     * Глобальный уникальный идентификатор адресного объекта
     *
     * @var string
     *
     * @ORM\Column(type="string", length=36, nullable=false, unique=true)
     */
    protected $aoguid;

    /**
     * @return string
     */
    public function getAoguid(): string
    {
        return $this->aoguid;
    }

    /**
     * @param string $aoguid
     *
     * @return $this
     */
    public function setAoguid(string $aoguid): self
    {
        $this->aoguid = $aoguid;

        return $this;
    }
}
