<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait OffnameTrait
{
    /**
     * Официальное наименование
     *
     * @var string
     *
     * @ORM\Column(type="string", length=120, nullable=false)
     */
    protected $offname;

    /**
     * @return string
     */
    public function getOffname(): string
    {
        return $this->offname;
    }

    /**
     * @param string $offname
     *
     * @return $this
     */
    public function setOffname(string $offname): self
    {
        $this->offname = trim($offname);

        return $this;
    }
}
