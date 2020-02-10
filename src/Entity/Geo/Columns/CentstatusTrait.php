<?php

declare(strict_types=1);

namespace App\Entity\Geo\Columns;

trait CentstatusTrait
{
    /**
     * Статус центра
     *
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $centstatus;

    /**
     * @return int
     */
    public function getCentstatus(): int
    {
        return $this->centstatus;
    }

    /**
     * @param int $centstatus
     *
     * @return $this
     */
    public function setCentstatus(int $centstatus): self
    {
        $this->centstatus = $centstatus;

        return $this;
    }
}
