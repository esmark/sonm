<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="baskets",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"user_id", "item_id"}),
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
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="baskets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $item;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="baskets", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * Basket constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
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
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     *
     * @return $this
     */
    public function setItem(Item $item): self
    {
        $this->item = $item;

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
}
