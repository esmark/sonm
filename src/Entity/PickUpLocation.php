<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="pick_up_locations",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"title"}),
 *      },
 * )
 */
class PickUpLocation
{
    use ColumnTrait\Id;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\IsEnabled;
    use ColumnTrait\UserNotNull;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotNull(message="This value can not be empty.")
     */
    protected $address;

    /**
     * Есть возможность приёма наличными
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $has_cash_acceptance;

    /**
     * Есть возможность приёма оплыты платёжных карт.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $has_pos_terminal;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true)
     */
    protected $latitude;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=14, scale=11, nullable=true)
     */
    protected $longitude;

    /**
     * @var Cooperative[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Cooperative", mappedBy="pick_up_locations")
     */
    protected $cooperatives;

    public function __construct()
    {
        $this->cooperatives        = new ArrayCollection();
        $this->created_at          = new \DateTime();
        $this->is_enabled          = true;
        $this->has_cash_acceptance = false;
        $this->has_pos_terminal    = false;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getLatitude(): ?float
    {
        return $this->latitude ? (float) $this->latitude : null;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude ? (float) $this->longitude : null;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Cooperative[]|Collection
     */
    public function getCooperatives(): Collection
    {
        return $this->cooperatives;
    }

    /**
     * @param Cooperative[]|Collection $cooperatives
     *
     * @return $this
     */
    public function setCooperatives(Collection $cooperatives): self
    {
        $this->cooperatives = $cooperatives;

        return $this;
    }

    public function isHasCashAcceptance(): bool
    {
        return $this->has_cash_acceptance;
    }

    public function setHasCashAcceptance(bool $has_cash_acceptance): self
    {
        $this->has_cash_acceptance = $has_cash_acceptance;

        return $this;
    }

    public function isHasPosTerminal(): bool
    {
        return $this->has_pos_terminal;
    }

    public function setHasPosTerminal(bool $has_pos_terminal): self
    {
        $this->has_pos_terminal = $has_pos_terminal;

        return $this;
    }
}
