<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\Common\Collections\ArrayCollection;
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
 *    fields="name",
 *    message="Name is already exists"
 * )
 */
class Cooperative
{
    use ColumnTrait\Id;
    use ColumnTrait\NameUnique;
    use ColumnTrait\TitleNotBlank;
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
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $register_date;

    /**
     * @var CooperativeHistory[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CooperativeHistory", mappedBy="cooperative", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $history;

    /**
     * @var Item[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="cooperative", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $items;

    /**
     * @var CooperativeMember[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CooperativeMember", mappedBy="cooperative", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "ASC"})
     */
    protected $members;

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
        $this->status       = self::STATUS_PENDING;
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
     * @return CooperativeHistory[]|ArrayCollection
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param CooperativeHistory[]|ArrayCollection $history
     *
     * @return $this
     */
    public function setHistory($history): self
    {
        $this->history = $history;

        return $this;
    }

    /**
     * @return Item[]|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item[]|ArrayCollection $items
     *
     * @return $this
     */
    public function setItems($items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return CooperativeMember[]|ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param CooperativeMember[]|ArrayCollection $members
     *
     * @return $this
     */
    public function setMembers($members): self
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
     * @return \DateTime
     */
    public function getRegisterDate(): ?\DateTime
    {
        return $this->register_date;
    }

    /**
     * @param \DateTime $register_date
     *
     * @return $this
     */
    public function setRegisterDate(\DateTime $register_date): self
    {
        $this->register_date = $register_date;

        return $this;
    }
}
