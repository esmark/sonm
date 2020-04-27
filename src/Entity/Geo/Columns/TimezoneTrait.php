<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait TimezoneTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    protected $timezone;

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = trim($timezone);

        return $this;
    }
}
