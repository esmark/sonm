<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use App\Entity\Geo\City;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CooperativeRepository")
 * @ORM\Table("cooperatives",
 *      indexes={
 *          @ORM\Index(columns={"inn"}),
 *          @ORM\Index(columns={"kpp"}),
 *          @ORM\Index(columns={"ogrn"}),
 *          @ORM\Index(columns={"created_at"}),
 *      },
 * )
 *
 * @UniqueEntity(
 *    fields="slug",
 *    message="Slug is already exists"
 * )
 *
 * @UniqueEntity(
 *    fields="title",
 *    message="Title is already exists"
 * )
 */
class Cooperative
{
    use ColumnTrait\Id;
    use ColumnTrait\Description;
    use ColumnTrait\Address;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UpdatedAt;
    use StatusTrait;

    const STATUS_PENDING   = 0;
    const STATUS_ACTIVE    = 2;
    const STATUS_INACTIVE  = 3;
    const STATUS_DECLINE   = 4;
    static protected $status_values = [
        self::STATUS_PENDING    => 'Ожидает заверения',
        self::STATUS_ACTIVE     => 'Действующий',
        self::STATUS_INACTIVE   => 'Не действующий',
        self::STATUS_DECLINE    => 'Заверение отклонено',
    ];

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     * @Assert\NotBlank()
     */
    protected $ogrn;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     * @Assert\NotBlank()
     */
    protected $inn;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint")
     * @Assert\NotBlank()
     */
    protected $kpp;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $director;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $register_date;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=190, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=190, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var City|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\City", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $city;

    /**
     * @var CooperativeHistory[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CooperativeHistory", mappedBy="cooperative", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $history;

    /**
     * @var Product[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="cooperative", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $products;

    /**
     * @var CooperativeMember[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CooperativeMember", mappedBy="cooperative", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "ASC"})
     */
    protected $members;

    /**
     * @var PickUpLocation[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="PickUpLocation", inversedBy="cooperatives", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="cooperatives_pick_up_locations_relations")
     */
    protected $pick_up_locations;

    /**
     * @var Program[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Program", inversedBy="cooperatives", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="cooperatives_programs_relations")
     */
    protected $programs;

    /**
     * @var TaxRate[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="TaxRate", inversedBy="cooperatives", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="cooperatives_tax_rates_relations")
     */
    protected $taxRates;

    /**
     * @var TaxRate|null
     *
     * @ORM\ManyToOne(targetEntity="TaxRate")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $taxRateDefault;

    /**
     * Cooperative constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->director     = '';
        $this->items        = new ArrayCollection();
        $this->members      = new ArrayCollection();
        $this->inn          = 0;
        $this->kpp          = 0;
        $this->ogrn         = 0;
        $this->slug         = '';
        $this->status       = self::STATUS_PENDING;
        $this->title        = '';
        $this->pick_up_locations = new ArrayCollection();
        $this->programs          = new ArrayCollection();
        $this->taxRates          = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }

    /**
     * @return int
     */
    public function getOgrn(): int
    {
        return (int) $this->ogrn;
    }

    /**
     * @param int $ogrn
     *
     * @return $this
     */
    public function setOgrn(int $ogrn): self
    {
        $this->ogrn = $ogrn;

        return $this;
    }

    /**
     * @return int
     */
    public function getInn(): int
    {
        return (int) $this->inn;
    }

    /**
     * @param int $inn
     *
     * @return $this
     */
    public function setInn(int $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * @return int
     */
    public function getKpp(): int
    {
        return (int) $this->kpp;
    }

    /**
     * @param int $kpp
     *
     * @return $this
     */
    public function setKpp(int $kpp): self
    {
        $this->kpp = $kpp;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirector(): string
    {
        return $this->director;
    }

    /**
     * @param string $director
     *
     * @return $this
     */
    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return CooperativeHistory[]|Collection
     */
    public function getHistory(): Collection
    {
        return $this->history;
    }

    /**
     * @param CooperativeHistory[]|Collection $history
     *
     * @return $this
     */
    public function setHistory(Collection $history): self
    {
        $this->history = $history;

        return $this;
    }

    /**
     * @return Product[]|Collection
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product[]|Collection $products
     *
     * @return $this
     */
    public function setProducts(Collection $products): self
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @return CooperativeMember[]|Collection
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    /**
     * @param CooperativeMember[]|Collection $members
     *
     * @return $this
     */
    public function setMembers(Collection $members): self
    {
        $this->members = $members;

        return $this;
    }

    /**
     * @param User $user
     *
     * @return CooperativeMember|null
     */
    public function getMemberByUser(User $user): ?CooperativeMember
    {
        foreach ($this->getMembers() as $member) {
            if ($member->getUser()->getId() == $user->getId()) {
                return $member;
            }
        }

        return null;
    }

    /**
     * @return \DateTime|null
     */
    public function getRegisterDate(): ?\DateTime
    {
        return $this->register_date;
    }

    /**
     * @param \DateTime|null $register_date
     *
     * @return $this
     */
    public function setRegisterDate(?\DateTime $register_date): self
    {
        $this->register_date = $register_date;

        return $this;
    }

    /**
     * @return PickUpLocation[]|Collection
     */
    public function getPickUpLocations(): Collection
    {
        return $this->pick_up_locations;
    }

    /**
     * @param PickUpLocation[]|Collection $pick_up_locations
     *
     * @return $this
     */
    public function setPickUpLocations(Collection $pick_up_locations): self
    {
        $this->pick_up_locations = $pick_up_locations;

        return $this;
    }

    /**
     * @return Program[]|Collection
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    /**
     * @param Program[]|Collection $programs
     *
     * @return $this
     */
    public function setPrograms(Collection $programs): self
    {
        $this->programs = $programs;

        return $this;
    }

    /**
     * @return TaxRate[]|Collection
     */
    public function getTaxRates(): Collection
    {
        return $this->taxRates;
    }

    /**
     * @param TaxRate[]|Collection $taxRates
     *
     * @return $this
     */
    public function setTaxRates(Collection $taxRates): self
    {
        $this->taxRates = $taxRates;

        return $this;
    }

    /**
     * @return TaxRate|null
     */
    public function getTaxRateDefault(): ?TaxRate
    {
        return $this->taxRateDefault;
    }

    /**
     * @param TaxRate|null $taxRateDefault
     *
     * @return $this
     */
    public function setTaxRateDefault(?TaxRate $taxRateDefault): self
    {
        $this->taxRateDefault = $taxRateDefault;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     *
     * @return $this
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
