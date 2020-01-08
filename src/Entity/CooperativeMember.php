<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity()
 * @ORM\Table("cooperatives_members",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"user_id", "cooperative_id"}),
 *      }
 * )
 *
 * @UniqueEntity(fields={"user", "cooperative"}, message="User-Cooperative must be unique")
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
     * Доступ для публикации и изменению товаров от имени кооператива
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false,  options={"default":0})
     */
    protected $is_allow_marketplace;

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="members", cascade={"persist"})
     */
    protected $cooperative;

    /**
     * CooperativeMember constructor.
     */
    public function __construct(?Cooperative $cooperative = null, ?User $user = null)
    {
        $this->created_at   = new \DateTime();
        $this->is_allow_marketplace = false;
        $this->status       = self::STATUS_PENDING;
        $this->cooperative  = $cooperative;
        $this->user         = $user;
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

    /**
     * @return bool
     */
    public function isIsAllowMarketplace(): bool
    {
        return $this->is_allow_marketplace;
    }

    /**
     * @param bool $is_allow_marketplace
     *
     * @return $this
     */
    public function setIsAllowMarketplace(bool $is_allow_marketplace): self
    {
        $this->is_allow_marketplace = $is_allow_marketplace;

        return $this;
    }
}
