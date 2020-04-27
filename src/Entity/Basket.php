<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BasketRepository")
 * @ORM\Table(name="baskets",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"user_id", "product_variant_id"}),
 *      }
 * )
 */
class Basket
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;

    /**
     * Количество
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $quantity;

    /**
     * @var ProductVariant
     *
     * @ORM\ManyToOne(targetEntity="ProductVariant", inversedBy="baskets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $productVariant;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="baskets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProductVariant(): ProductVariant
    {
        return $this->productVariant;
    }

    public function setProductVariant(ProductVariant $productVariant): self
    {
        $this->productVariant = $productVariant;

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
