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
 * @ORM\Entity()
 * @ORM\Table(name="products_variants",
 *      indexes={
 *          @ORM\Index(columns={"status"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"title", "product_id"}),
 *          @ORM\UniqueConstraint(columns={"sku", "cooperative_id"}),
 *      }
 * )
 *
 * @UniqueEntity(fields={"title", "product"}, message="Title must be unique")
 * @UniqueEntity(fields={"sku", "cooperative"}, message="SKU must be unique")
 */
class ProductVariant
{
    use ColumnTrait\Uuid;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\UserNotNull; // Кто добавил вариант
    use StatusTrait;

    // @todo продумать статусы товара и вариантов
    const STATUS_NOT_AVAILABLE  = 0;
    const STATUS_AVAILABLE      = 1;
    const STATUS_RESERVE        = 2;
    const STATUS_ON_DEMAND      = 3;
    static protected $status_values = [
        self::STATUS_AVAILABLE      => 'Есть в наличии',
        self::STATUS_ON_DEMAND      => 'Под заказ',
        self::STATUS_RESERVE        => 'Резерв',
        self::STATUS_NOT_AVAILABLE  => 'Нет в наличии',
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $price;

    /**
     * Актикул (единица учета запасов)
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $sku;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $status;

    /**
     * Количество
     *
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity;

    /**
     * Ширина
     *
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $width;

    /**
     * Высота
     *
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $height;

    /**
     * Глубина
     *
     * @var int|null
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $depth;

    /**
     * Вес в кг.
     *
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=8, scale=5, nullable=true)
     */
    protected $weight;

    /**
     * Кол-во в резерве
     *
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity_reserved;

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative", fetch="EXTRA_LAZY")
     */
    protected $cooperative;

    /**
     * @var Basket[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Basket", mappedBy="productVariant", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $baskets;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="variants", cascade={"persist"})
     */
    protected $product;

    public function __construct()
    {
        $this->baskets      = new ArrayCollection();
        $this->created_at   = new \DateTime();
        $this->status       = self::STATUS_AVAILABLE;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getPriceTotal(): ?int
    {
        if (empty($this->quantity)) {
            return $this->price;
        }

        return $this->price * $this->quantity;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getQuantityReserved(): ?int
    {
        return $this->quantity_reserved;
    }

    public function setQuantityReserved(?int $quantity_reserved): self
    {
        $this->quantity_reserved = $quantity_reserved;

        return $this;
    }

    /**
     * @return Basket[]|Collection
     */
    public function getBaskets(): Collection
    {
        return $this->baskets;
    }

    /**
     * @param Basket[]|Collection $baskets
     *
     * @return $this
     */
    public function setBaskets(Collection $baskets): self
    {
        $this->baskets = $baskets;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
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

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->depth;
    }

    public function setDepth(?int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }
}
