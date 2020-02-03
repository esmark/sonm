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
 *          @ORM\Index(columns={"amount"}),
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"payment_status"}),
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
    use ColumnTrait\Ipv4;
    use ColumnTrait\Comment;
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
     * Сумма заказа
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16, nullable=false, unique=true)
     */
    protected $token;

    /**
     * Статус оплаты
     *
     * @var string
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $paymentStatus;

    /**
     * Статус доставки
     *
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    protected $shippingStatus;

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative")
     */
    protected $cooperative;

    /**
     * Адрес доставки
     *
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address")
     */
    protected $shippingAddress;

    /**
     * @var OrderLine[]|Collection
     *
     * @ORM\OneToMany(targetEntity="OrderLine", mappedBy="order", cascade={"persist"}, fetch="EXTRA_LAZY")
     * ORM\OrderBy({"title" = "ASC"})
     */
    protected $lines;

    /**
     * @var Payment[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="order", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $payments;

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
        $this->created_at    = new \DateTime();
        $this->status        = self::STATUS_CART;
        $this->lines         = new ArrayCollection();
        $this->payments      = new ArrayCollection();
        $this->paymentStatus = Payment::STATUS_CART;
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

    /**
     * @return OrderLine[]|Collection
     */
    public function getLines(): Collection
    {
        return $this->lines;
    }

    /**
     * @param OrderLine[]|Collection $lines
     *
     * @return $this
     */
    public function setLines(Collection $lines): self
    {
        $this->lines = $lines;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    /**
     * @param string $paymentStatus
     *
     * @return $this
     */
    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getShippingStatus(): string
    {
        return $this->shippingStatus;
    }

    /**
     * @param string $shippingStatus
     *
     * @return $this
     */
    public function setShippingStatus(string $shippingStatus): self
    {
        $this->shippingStatus = $shippingStatus;

        return $this;
    }

    /**
     * @return Address
     */
    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    /**
     * @param Address $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress(Address $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    /**
     * @return Payment[]|Collection
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    /**
     * @param Payment[]|Collection $payments
     *
     * @return $this
     */
    public function setPayments(Collection $payments): self
    {
        $this->payments = $payments;

        return $this;
    }
}
