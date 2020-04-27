<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CooperativeMemberRepository")
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
    use ColumnTrait\CreatedAt;
    use StatusTrait;

    const STATUS_PENDING_ASSOC  = 0;
    const STATUS_PENDING_REAL   = 1;
    const STATUS_ASSOC          = 2;
    const STATUS_REAL           = 3;
    const STATUS_CHAIRMAN       = 4;
    static protected $status_values = [
        self::STATUS_PENDING_ASSOC  => 'Ожидает заверения в качестве АП',
        self::STATUS_PENDING_REAL   => 'Ожидает заверения в качестве ДП',
        self::STATUS_ASSOC          => 'Ассоциированный пайщик',
        self::STATUS_REAL           => 'Действительный пайщик',
        self::STATUS_CHAIRMAN       => 'Председатель',
    ];

    /**
     * Доступ для публикации и изменению товаров от имени кооператива
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $is_allow_marketplace;

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="members", cascade={"persist"})
     */
    protected $cooperative;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="members", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    public function __construct(?Cooperative $cooperative = null, ?User $user = null)
    {
        $this->created_at   = new \DateTime();
        $this->is_allow_marketplace = false;
        $this->status       = self::STATUS_PENDING_ASSOC;
        $this->cooperative  = $cooperative;
        $this->user         = $user;
    }

    public function getCooperative(): Cooperative
    {
        return $this->cooperative;
    }

    public function setCooperative(Cooperative $cooperative): self
    {
        $this->cooperative = $cooperative;

        return $this;
    }

    public function isIsAllowMarketplace(): bool
    {
        return $this->is_allow_marketplace;
    }

    public function setIsAllowMarketplace(bool $is_allow_marketplace): self
    {
        $this->is_allow_marketplace = $is_allow_marketplace;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
