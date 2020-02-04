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

    const CHECKOUT_CART              = 'cart';              // Начало оформления заказа
    const CHECKOUT_COMPLETED         = 'completed';         // Оформлен
    const CHECKOUT_ADDRESSED         = 'addressed';         // Выбор адреса доставки
    const CHECKOUT_SHIPPING_SELECTED = 'shipping_selected'; // Метод доставки выбран
    const CHECKOUT_SHIPPING_SKIPPED  = 'shipping_skipped';  // Метод доставки пропущен
    const CHECKOUT_PAYMENT_SELECTED  = 'payment_selected';  // Метод платежа выбран
    const CHECKOUT_PAYMENT_SKIPPED   = 'payment_skipped';   // Метод платежа пропущен

    const SHIPPING_CART              = 'cart';              // Начало оформления заказа
    const SHIPPING_READY             = 'ready';             // Готово к отправке
    const SHIPPING_CANCELLED         = 'cancelled';         // Доставка отменена
    const SHIPPING_PARTIALLY_SHIPPED = 'partially_shipped'; // Частично отгруженный @todo ?
    const SHIPPING_SHIPPED           = 'shipped';           // Отгруженный

    const PAYMENT_CART                  = 'cart';                 // Начало оформления заказа
    const PAYMENT_AWAITING_PAYMENT      = 'awaiting_payment';     // Ожидание оплаты
    const PAYMENT_PARTIALLY_AUTHORIZED  = 'partially_authorized'; // Частичнй @todo ?
    const PAYMENT_AUTHORIZED            = 'authorized';           // @todo ?
    const PAYMENT_PARTIALLY_PAID        = 'partially_paid';       // Частичнй оплачен @todo ?
    const PAYMENT_CANCELLED             = 'cancelled';            // Отменён
    const PAYMENT_PAID                  = 'paid';                 // Оплачен
    const PAYMENT_PARTIALLY_REFUNDED    = 'partially_refunded';   // Частичнй возврат @todo ?
    const PAYMENT_REFUNDED              = 'refunded';             // Возврат

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
     * @ORM\Column(type="string", length=16, nullable=false)
     */
    protected $checkoutStatus;

    /**
     * Дата завершения оформления заказа
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $checkoutCompletedAt;

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
     * @ORM\Column(type="string", length=24, nullable=false)
     */
    protected $paymentStatus;

    /**
     * Статус доставки
     *
     * @var string
     *
     * @ORM\Column(type="string", length=24, nullable=false)
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
     * @var Address|null
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
        $this->checkoutStatus = self::CHECKOUT_CART;
        $this->created_at     = new \DateTime();
        $this->status         = self::STATUS_CART;
        $this->lines          = new ArrayCollection();
        $this->payments       = new ArrayCollection();
        $this->paymentStatus  = self::PAYMENT_CART;
        $this->shippingStatus = self::SHIPPING_CART;
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
     * @return Address|null
     */
    public function getShippingAddress(): ?Address
    {
        return $this->shippingAddress;
    }

    /**
     * @param Address|null $shippingAddress
     *
     * @return $this
     */
    public function setShippingAddress(?Address $shippingAddress): self
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

    /**
     * @return string
     */
    public function getCheckoutStatus(): string
    {
        return $this->checkoutStatus;
    }

    /**
     * @param string $checkoutStatus
     *
     * @return $this
     */
    public function setCheckoutStatus(string $checkoutStatus): self
    {
        $this->checkoutStatus = $checkoutStatus;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCheckoutCompletedAt(): \DateTime
    {
        return $this->checkoutCompletedAt;
    }

    /**
     * @param \DateTime $checkoutCompletedAt
     *
     * @return $this
     */
    public function setCheckoutCompletedAt(\DateTime $checkoutCompletedAt): self
    {
        $this->checkoutCompletedAt = $checkoutCompletedAt;

        return $this;
    }
}
