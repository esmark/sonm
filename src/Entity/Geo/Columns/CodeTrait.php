<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait CodeTrait
{
    /**
     * Код адресного элемента одной строкой с признаком актуальности из классификационного кода
     *
     * @var string
     *
     * @ORM\Column(type="string", length=17, nullable=false)
     */
    protected $code;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = trim($code);

        return $this;
    }
}
