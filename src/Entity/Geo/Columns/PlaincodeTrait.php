<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait PlaincodeTrait
{
    /**
     * Код адресного элемента одной строкой без признака актуальности (последних двух цифр)
     *
     * @var string
     *
     * @ORM\Column(type="string", length=15, nullable=false)
     */
    protected $plaincode;

    /**
     * @return string
     */
    public function getPlaincode(): string
    {
        return $this->plaincode;
    }

    /**
     * @param string $plaincode
     *
     * @return $this
     */
    public function setPlaincode(string $plaincode): self
    {
        $this->plaincode = $plaincode;

        return $this;
    }
}
