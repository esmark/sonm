<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * PickUpLocation constructor.
     */
    public function __construct()
    {
        $this->cooperatives        = new ArrayCollection();
        $this->created_at          = new \DateTime();
        $this->is_enabled          = true;
        $this->has_cash_acceptance = false;
        $this->has_pos_terminal    = false;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude ? (float) $this->latitude : null;
    }

    /**
     * @param float|null $latitude
     *
     * @return $this
     */
    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude ? (float) $this->longitude : null;
    }

    /**
     * @param float|null $longitude
     *
     * @return $this
     */
    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Cooperative[]|ArrayCollection
     */
    public function getCooperatives()
    {
        return $this->cooperatives;
    }

    /**
     * @param Cooperative[]|ArrayCollection $cooperatives
     *
     * @return $this
     */
    public function setCooperatives($cooperatives): self
    {
        $this->cooperatives = $cooperatives;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasCashAcceptance(): bool
    {
        return $this->has_cash_acceptance;
    }

    /**
     * @param bool $has_cash_acceptance
     *
     * @return $this
     */
    public function setHasCashAcceptance(bool $has_cash_acceptance): self
    {
        $this->has_cash_acceptance = $has_cash_acceptance;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasPosTerminal(): bool
    {
        return $this->has_pos_terminal;
    }

    /**
     * @param bool $has_pos_terminal
     *
     * @return $this
     */
    public function setHasPosTerminal(bool $has_pos_terminal): self
    {
        $this->has_pos_terminal = $has_pos_terminal;

        return $this;
    }
}
