<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Платежи
 *
 * @ORM\Entity()
 * @ORM\Table(name="payments",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"status"}),
 *      }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Payment
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use StatusTrait;

    const STATUS_CART               = 0;
    const STATUS_AWAITING_PAYMENT   = 1;
    const STATUS_PAID               = 2;
    const STATUS_CANCELLED          = 3;
    static protected $status_values = [
        self::STATUS_CART               => 'На оформлении', // @todo не нужен
        self::STATUS_AWAITING_PAYMENT   => 'Ожидание оплаты',
        self::STATUS_PAID               => 'Оплачено',
        self::STATUS_CANCELLED          => 'Отменён',
    ];

    /**
     * Сумма
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount;

    /**
     * @var PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="PaymentMethod")
     */
    protected $method;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="payments", cascade={"persist"})
     */
    protected $order;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="payments", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->status       = self::STATUS_AWAITING_PAYMENT;
    }

    public function getStatusBadgeColor(): string
    {
        switch ($this->status) {
            case 1:
                return 'info';
            case 2:
                return 'success';
            case 3:
                return 'danger';
        }

        return 'light';
    }

    public function getMethod(): PaymentMethod
    {
        return $this->method;
    }

    public function setMethod(PaymentMethod $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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
