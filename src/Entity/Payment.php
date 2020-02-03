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
        self::STATUS_CART               => 'На оформлении',
        self::STATUS_AWAITING_PAYMENT   => 'Ожидание оплаты',
        self::STATUS_PAID               => 'Оплачено',
        self::STATUS_CANCELLED          => 'Отменён',
    ];

    /**
     * @var PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="PaymentMethod")
     */
    protected $paymentMethod;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="payments", cascade={"persist"})
     */
    protected $order;

    /**
     * Payment constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod(): PaymentMethod
    {
        return $this->paymentMethod;
    }

    /**
     * @param PaymentMethod $paymentMethod
     *
     * @return $this
     */
    public function setPaymentMethod(PaymentMethod $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     *
     * @return $this
     */
    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }
}
