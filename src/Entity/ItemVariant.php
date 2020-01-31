<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="items_variants",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"title", "item_id"}),
 *      }
 * )
 */
class ItemVariant
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\TitleNotBlank;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $price;

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
     * @var Basket[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Basket", mappedBy="items_variants", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $baskets;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="variants")
     */
    protected $item;

    /**
     * ItemVariant constructor.
     */
    public function __construct()
    {
        $this->baskets      = new ArrayCollection();
        $this->created_at   = new \DateTime();
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
}
