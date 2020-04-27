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
    const CHECKOUT_ADDRESSED         = 'addressed';         // Выбран адрес доставки или точки самовывоза
    const CHECKOUT_SHIPPING_SELECTED = 'shipping_selected'; // Метод доставки выбран
    const CHECKOUT_SHIPPING_SKIPPED  = 'shipping_skipped';  // Метод доставки пропущен
    const CHECKOUT_PAYMENT_SELECTED  = 'payment_selected';  // Метод платежа выбран
    const CHECKOUT_PAYMENT_SKIPPED   = 'payment_skipped';   // Метод платежа пропущен

    const PAYMENT_CART                  = 'cart';                 // Начало оформления заказа
    const PAYMENT_AWAITING_PAYMENT      = 'awaiting_payment';     // Ожидание оплаты
    const PAYMENT_PARTIALLY_AUTHORIZED  = 'partially_authorized'; // Частичнй @todo ?
    const PAYMENT_AUTHORIZED            = 'authorized';           // @todo ?
    const PAYMENT_PARTIALLY_PAID        = 'partially_paid';       // Частичнй оплачен @todo ?
    const PAYMENT_CANCELLED             = 'cancelled';            // Отменён
    const PAYMENT_PAID                  = 'paid';                 // Оплачен
    const PAYMENT_PARTIALLY_REFUNDED    = 'partially_refunded';   // Частичнй возврат @todo ?
    const PAYMENT_REFUNDED              = 'refunded';             // Возврат

    const SHIPPING_CART              = 'cart';              // Начало оформления заказа
    const SHIPPING_READY             = 'ready';             // Готово к отправке
    const SHIPPING_CANCELLED         = 'cancelled';         // Доставка отменена
    const SHIPPING_PARTIALLY_SHIPPED = 'partially_shipped'; // Частично отгруженный @todo ?
    const SHIPPING_SHIPPED           = 'shipped';           // Отгруженный

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
     * @ORM\Column(type="string", length=24, nullable=false)
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
     * Трек-номер для отслеживания доставки
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $shippingTracking;

    /**
     * Стоимость доставки
     *
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    protected $shippingPrice;

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
     * @var Payment[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="order", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $payments;

    /**
     * Адрес доставки
     *
     * Если NULL, значит "самовывоз"
     *
     * @var Address|null
     *
     * @ORM\ManyToOne(targetEntity="Address", inversedBy="orders", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $shippingAddress;

    /**
     * Точка самовывоза
     *
     * Если NULL, значит доставляется по адресу
     *
     * @var PickUpLocation|null
     *
     * @ORM\ManyToOne(targetEntity="PickUpLocation")
     */
    protected $shippingPickUpLocation;

    /**
     * Если NULL, значит товар не требует физической доставки.
     *
     * @var ShippingMethod|null
     *
     * @ORM\ManyToOne(targetEntity="ShippingMethod", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $shippingMethod;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    public function __construct()
    {
        $this->checkoutStatus = self::CHECKOUT_CART;
        $this->created_at     = new \DateTime();
        $this->status         = self::STATUS_CART;
        $this->lines          = new ArrayCollection();
        $this->payments       = new ArrayCollection();
        $this->paymentStatus  = self::PAYMENT_CART;
        $this->shippingPrice  = 0;
        $this->shippingStatus = self::SHIPPING_CART;
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

    public function getUser(): User
    {
        return $this->user;
    }

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

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getShippingStatus(): string
    {
        return $this->shippingStatus;
    }

    public function setShippingStatus(string $shippingStatus): self
    {
        $this->shippingStatus = $shippingStatus;

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

    public function getCheckoutStatus(): string
    {
        return $this->checkoutStatus;
    }

    public function setCheckoutStatus(string $checkoutStatus): self
    {
        $this->checkoutStatus = $checkoutStatus;

        return $this;
    }

    public function getCheckoutCompletedAt(): \DateTime
    {
        return $this->checkoutCompletedAt;
    }

    public function setCheckoutCompletedAt(\DateTime $checkoutCompletedAt): self
    {
        $this->checkoutCompletedAt = $checkoutCompletedAt;

        return $this;
    }

    public function getShippingTracking(): ?string
    {
        return $this->shippingTracking;
    }

    public function setShippingTracking(?string $shippingTracking): self
    {
        $this->shippingTracking = $shippingTracking;

        return $this;
    }

    public function getShippingAddress(): ?Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?Address $shippingAddress): self
    {
        $this->shippingAddress = $shippingAddress;

        return $this;
    }

    public function getShippingPickUpLocation(): ?PickUpLocation
    {
        return $this->shippingPickUpLocation;
    }

    public function setShippingPickUpLocation(?PickUpLocation $shippingPickUpLocation): self
    {
        $this->shippingPickUpLocation = $shippingPickUpLocation;

        return $this;
    }

    public function getShippingMethod(): ?ShippingMethod
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(?ShippingMethod $shippingMethod): self
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    public function getShippingPrice(): int
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice(int $shippingPrice): self
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }
}
