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
 * Товары и услуги
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"status"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"cooperative_id", "title"}),
 *      }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"cooperative", "title"}, message="Title must be unique")
 */
class Product
{
    use ColumnTrait\Uuid;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\Description;
    use ColumnTrait\UserNotNull; // Кто добавил товар
    use StatusTrait;

    // Еденицы измерения
    const MEASURE_NONE  = 0;
    const MEASURE_PIECE = 1;
    const MEASURE_GRAM  = 2;
    const MEASURE_KG    = 3;
    const MEASURE_LITRE = 4;
    static protected $measure_values = [
        self::MEASURE_NONE  => 'нет',
        self::MEASURE_PIECE => 'шт',
        self::MEASURE_GRAM  => 'гр',
        self::MEASURE_KG    => 'кг',
        self::MEASURE_LITRE => 'л',
    ];

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
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false,  options={"default":1})
     */
    protected $is_enabled;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $short_description;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $status;

    /**
     * Еденицы измерения
     *
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $measure;

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
     * ИД файла в медиалибе
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $image_id;

    /**
     * Минимальная цена из доступных вариантов
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $priceMin;

    /**
     * Максимальная цена из доступных вариантов
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $priceMax;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $category;

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="products", cascade={"persist"})
     */
    protected $cooperative;

    /**
     * @var TaxRate|null
     *
     * @ORM\ManyToOne(targetEntity="TaxRate")
     */
    protected $taxRate;

    /**
     * @var ProductVariant[]|Collection
     *
     * @ORM\OneToMany(targetEntity="ProductVariant", mappedBy="product", cascade={"persist"})
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $variants;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->measure    = self::MEASURE_NONE;
        $this->status     = self::STATUS_AVAILABLE;
        $this->variants   = new ArrayCollection();
        $this->priceMin   = 0;
        $this->priceMax   = 0;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->title;
    }

    /**
     * @ORM\PreFlush()
     */
    public function preFlush()
    {
        foreach ($this->getVariants() as $variant) {
            if ($this->priceMin == 0) {
                $this->priceMin = $variant->getPrice();
            }

            if ($this->priceMax == 0) {
                $this->priceMax = $variant->getPrice();
            }

            if ($variant->getPrice() < $this->priceMin) {
                $this->priceMin = $variant->getPrice();
            }

            if ($variant->getPrice() > $this->priceMax) {
                $this->priceMax = $variant->getPrice();
            }
        }

        /*
        if ($this->getMeasure() == self::MEASURE_NONE) {
            $this->setQuantity(null);
            $this->setQuantityReserved(null);
        }
        */
    }

    /**
     * @return bool
     */
    public function isStatusAccessToOrder(): bool
    {
        if ($this->status == self::STATUS_AVAILABLE or $this->status == self::STATUS_ON_DEMAND) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function getMeasure(): int
    {
        return $this->measure;
    }

    /**
     * @return string
     */
    public function getMeasureAsText(): string
    {
        return self::$measure_values[$this->measure];
    }

    /**
     * @param int $measure
     *
     * @return $this
     */
    public function setMeasure(int $measure): self
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * @return array
     */
    static public function getMeasureChoiceValues(): array
    {
        return self::$measure_values;
    }

    /**
     * @return string|null
     */
    public function getImageId(): ?string
    {
        return $this->image_id;
    }

    /**
     * @param string|null $image_id
     *
     * @return $this
     */
    public function setImageId(?string $image_id): self
    {
        $this->image_id = $image_id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    /**
     * @param string|null $short_description
     *
     * @return $this
     */
    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return $this
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
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
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     *
     * @return $this
     */
    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     *
     * @return $this
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDepth(): ?int
    {
        return $this->depth;
    }

    /**
     * @param int|null $depth
     *
     * @return $this
     */
    public function setDepth(?int $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->weight ? (float) $this->weight : null;
    }

    /**
     * @param float|null $weight
     *
     * @return $this
     */
    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @param ProductVariant $variant
     *
     * @return $this
     */
    public function addVariant(ProductVariant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
            $variant->setProduct($this);
        }

        return $this;
    }

    /**
     * @return ProductVariant[]|Collection
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    /**
     * @param ProductVariant[]|Collection $variants
     *
     * @return $this
     */
    public function setVariants(Collection $variants): self
    {
        $this->variants = $variants;

        return $this;
    }

    /**
     * @return string
     */
    public function getPriceRange(): string
    {
        if ($this->priceMin == $this->priceMax) {
            return (string) $this->priceMin;
        }

        return $this->priceMin . ' - ' . $this->priceMax;
    }

    /**
     * @return int
     */
    public function getPriceMin(): int
    {
        return $this->priceMin;
    }

    /**
     * @param int $priceMin
     *
     * @return $this
     */
    public function setPriceMin(int $priceMin): self
    {
        $this->priceMin = $priceMin;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceMax(): int
    {
        return $this->priceMax;
    }

    /**
     * @param int $priceMax
     *
     * @return $this
     */
    public function setPriceMax(int $priceMax): self
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    /**
     * @return TaxRate|null
     */
    public function getTaxRate(): ?TaxRate
    {
        return $this->taxRate;
    }

    /**
     * @param TaxRate|null $taxRate
     *
     * @return $this
     */
    public function setTaxRate(?TaxRate $taxRate): self
    {
        $this->taxRate = $taxRate;

        return $this;
    }
}
