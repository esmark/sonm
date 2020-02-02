<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Заказы
 *
 * @ORM\Entity()
 * @ORM\Table(name="orders",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"status"}),
 *      }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use StatusTrait;

    const STATUS_CART      = 0;
    const STATUS_NEW       = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;
    static protected $status_values = [
        self::STATUS_CART       => 'На оформлении',
        self::STATUS_NEW        => 'Новый',
        self::STATUS_CANCELLED  => 'Отменённый',
        self::STATUS_COMPLETED  => 'Выполненный',
    ];

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative")
     */
    protected $cooperative;

    /**
     * @var OrderLine[]|Collection
     *
     * @ORM\OneToMany(targetEntity="OrderLine", mappedBy="order", cascade={"persist"}, fetch="EXTRA_LAZY")
     * ORM\OrderBy({"title" = "ASC"})
     */
    protected $lines;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->status       = self::STATUS_NEW;
        $this->orders       = new ArrayCollection();
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
