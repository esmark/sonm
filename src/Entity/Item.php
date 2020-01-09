<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Товары и услуги
 *
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ORM\Table(name="items",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"price"}),
 *          @ORM\Index(columns={"status"}),
 *          @ORM\Index(columns={"title"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"user_id", "title"}),
 *      }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"user", "title"}, message="Title must be unique")
 */
class Item
{
    use ColumnTrait\Uuid;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\Description;
    use ColumnTrait\UserNotNull;

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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $price;

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
     * Количество
     *
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity;

    /**
     * Кол-во в резерве
     *
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity_reserved;

    /**
     * ИД файла в медиалибе
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    protected $image_id;

    /**
     * @var Basket[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Basket", mappedBy="item", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $baskets;

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
     * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="items", cascade={"persist"})
     */
    protected $cooperative;

    /**
     * Offer constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->is_enabled = true;
        $this->measure    = self::MEASURE_NONE;
        $this->status     = self::STATUS_AVAILABLE;
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
        if ($this->getMeasure() == self::MEASURE_NONE) {
            $this->setQuantity(null);
            $this->setQuantityReserved(null);
        }
    }

    /**
     * @return int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getPriceTotal(): ?int
    {
        if (empty($this->quantity)) {
            return $this->price;
        }

        return $this->price * $this->quantity;
    }

    /**
     * @param int $price
     *
     * @return $this
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusAsText(): string
    {
        return self::$status_values[$this->status];
    }

    /**
     * @return array
     */
    static public function getStatusChoiceValues(): array
    {
        return self::$status_values;
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
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     *
     * @return $this
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantityReserved(): ?int
    {
        return $this->quantity_reserved;
    }

    /**
     * @param int|null $quantity_reserved
     *
     * @return $this
     */
    public function setQuantityReserved(?int $quantity_reserved): self
    {
        $this->quantity_reserved = $quantity_reserved;

        return $this;
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
     * @return Basket[]|ArrayCollection
     */
    public function getBaskets()
    {
        return $this->baskets;
    }

    /**
     * @param Basket[]|ArrayCollection $baskets
     *
     * @return $this
     */
    public function setBaskets($baskets): self
    {
        $this->baskets = $baskets;

        return $this;
    }
}
