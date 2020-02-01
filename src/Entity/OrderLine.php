<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Товары в заказе
 *
 * @ORM\Entity()
 * @ORM\Table(name="orders_lines")
 */
class OrderLine
{
    use ColumnTrait\Id;

    /**
     * Количество
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $quantity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $price;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="lines", cascade={"persist"})
     */
    protected $order;

    /**
     * @var ProductVariant
     *
     * @ORM\ManyToOne(targetEntity="ProductVariant")
     */
    protected $productVariant;

    /**
     * OrderLine constructor.
     */
    public function __construct()
    {

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

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     *
     * @return $this
     */
    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return ProductVariant
     */
    public function getProductVariant(): ProductVariant
    {
        return $this->productVariant;
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return $this
     */
    public function setProductVariant(ProductVariant $productVariant): self
    {
        $this->productVariant = $productVariant;

        return $this;
    }
}
