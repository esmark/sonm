<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table("cooperatives_members",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      },
 * )
 */
class CooperativeMember
{
    use ColumnTrait\Id;
    use ColumnTrait\User;
    use ColumnTrait\CreatedAt;
    use StatusTrait;

    const STATUS_PENDING   = 0;
    const STATUS_ASSOCIATE = 2;
    const STATUS_FULL      = 3;
    const STATUS_CHAIRMAN  = 4;
    static protected $status_values = [
        self::STATUS_PENDING    => 'Ожидает заверения',
        self::STATUS_ASSOCIATE  => 'Ассоциативный член',
        self::STATUS_FULL       => 'Действительный член',
        self::STATUS_CHAIRMAN   => 'Председатель',
    ];

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="members", cascade={"persist"})
     */
    protected $cooperative;

    /**
     * CooperativeMember constructor.
     */
    public function __construct(?Cooperative $cooperative = null)
    {
        $this->created_at   = new \DateTime();
        $this->status       = self::STATUS_PENDING;
        $this->cooperative  = $cooperative;
    }

    /**
     * @return Cooperative
     */
    public function getCooperative(): Cooperative
    {
        return $this->cooperative;
    }

    /**
     * @param Cooperative $cooperative
     *
     * @return $this
     */
    public function setCooperative(Cooperative $cooperative): self
    {
        $this->cooperative = $cooperative;

        return $this;
    }
}