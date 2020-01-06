<?php

declare(strict_types=1);

namespace App\Doctrine;

trait StatusTrait
{
    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false, options={"unsigned"=true, "default":0})
     */
    protected $status;

    // START Status

    /**
     * @return array
     */
    static public function getStatusFormChoices(): array
    {
        return array_flip(self::$status_values);
    }

    /**
     * @return array
     */
    static public function getStatusValues(): array
    {
        return self::$status_values;
    }

    /**
     * @return bool
     */
    static public function isStatusExist($status): bool
    {
        if (isset(self::$status_values[$status])) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getStatusAsText(): string
    {
        if (isset(self::$status_values[$this->status])) {
            return self::$status_values[$this->status];
        }

        return 'N/A';
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    // END Status
}
