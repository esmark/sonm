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

    /**
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string|null $timezone
     *
     * @return $this
     */
    public function setTimezone(?string $timezone): self
    {
        $this->timezone = trim($timezone);

        return $this;
    }
}
