<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Worksheet;
use App\Entity\Geo\City;
use App\Model\UserModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table("users",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"firstname"}),
 *          @ORM\Index(columns={"lastname"}),
 *          @ORM\Index(columns={"last_login"}),
 *          @ORM\Index(columns={"is_enabled"}),
 *      },
 * )
 *
 * @UniqueEntity(
 *     fields="username_canonical",
 *     errorPath="username",
 *     message="Username is already exists"
 * )
 *
 * @UniqueEntity(
 *     fields="email_canonical",
 *     errorPath="email",
 *     message="Email is already exists"
 * )
 */
class User extends UserModel
{
    use ColumnTrait\Uuid;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\IsEnabled;

    /**
     * Имя
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="This value is not valid.")
     * @Assert\Length(max = 30)
     */
    protected $firstname;

    /**
     * Фамилия
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="This value is not valid.")
     * @Assert\Length(max = 30)
     */
    protected $lastname;

    /**
     * Отчество
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * Assert\NotNull(message="This value is not valid.")
     * @Assert\Length(max = 30)
     */
    protected $patronymic;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Assert\Length(max = 190)
     */
    protected $phone;

    /**
     * Образование
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $education;

    /**
     * Учебные заведения
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $schools;

    /**
     * Положение (производитель, потребитель, другое)
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = 30)
     */
    protected $state;

    /**
     * "Вы сейчас" (пенсионер, ИП и т.д.)
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(max = 30)
     */
    protected $status;

    /**
     * Каким направлением хозяйственной деятельности хотел бы заняться?
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $activity;

    /**
     * Выберите направление, в котором готовы принять участие:
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $participate;

    /**
     * Ссылки в социальных сетях (ВК, ОК, FB и т.д.)
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $social_links;

    /**
     * Профессиональные навыки
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $skills;

    /**
     * Есть ли у вас родственники в Кооперативе?
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, options={"default":0})
     */
    protected $has_relative;

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
     * @var integer|null
     *
     * @ORM\Column(type="integer", nullable=true, unique=true)
     */
    protected $telegram_user_id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max = 100)
     */
    protected $telegram_username;

    /**
     * @var Worksheet
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @deprecated пока не используется
     */
    protected $worksheet;

    /**
     * Анкета намерений - Что я могу дать ПСК?
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $purpose_give;

    /**
     * Анкета намерений - Что я хочу получить от работы в ПСК?
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $purpose_take;

    /**
     * Пригласивший пользователь
     *
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invited_users")
     */
    protected $invited_by_user;

    /**
     * Приглашение, которое было использовано.
     *
     * @var Invite|null
     *
     * @ORM\OneToOne(targetEntity="Invite", cascade={"persist"})
     */
    protected $invite;

    /**
     * Приглашенные пользователи
     *
     * @var User[]|Collection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="invited_by_user", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $invited_users;

    /**
     * @var City|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Geo\City", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $city;

    /**
     * Товары в корзинке
     *
     * @var Basket[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Basket", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $baskets;

    /**
     * @var Order[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $orders;

    /**
     * @var Payment[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $payments;

    /**
     * @var CooperativeMember[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CooperativeMember", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected $members;

    /**
     * @var UserGroup[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="UserGroup")
     * @ORM\JoinTable(name="users_groups_relations")
     */
    protected $groups;

    /**
     * @var UserOauth[]|Collection
     *
     * @ORM\OneToMany(targetEntity="UserOauth", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $oauths;

    public function __construct()
    {
        parent::__construct();

        $this->is_enabled    = true;
        $this->has_relative  = false;
        $this->email         = '';
        $this->baskets       = new ArrayCollection();
        $this->orders        = new ArrayCollection();
        $this->groups        = new ArrayCollection();
        $this->oauths        = new ArrayCollection();
        $this->payments      = new ArrayCollection();
        $this->invited_users = new ArrayCollection();
    }

    public function __toString(): string
    {
        if (empty($this->getFirstname()) and empty($this->getLastname())) {
            return $this->getUsername();
        }

        return $this->getFirstname().' '.$this->getLastname();
    }

    /**
     * Является ли юзер действующим участником какого-либо кооператива?
     */
    public function isMember(): bool
    {
        foreach ($this->members as $member) {
            if ($member->getStatus() == CooperativeMember::STATUS_PENDING_ASSOC
                or $member->getStatus() == CooperativeMember::STATUS_PENDING_REAL
            ) {
                // pending
            } else {
                return true;
            }
        }

        return false;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getTelegramUserId(): ?int
    {
        return $this->telegram_user_id;
    }

    public function setTelegramUserId(?int $telegram_user_id): self
    {
        $this->telegram_user_id = $telegram_user_id;

        return $this;
    }

    public function getTelegramUsername(): ?string
    {
        return $this->telegram_username;
    }

    public function setTelegramUsername(?string $telegram_username): self
    {
        $this->telegram_username = $telegram_username;

        return $this;
    }

    /**
     * @return UserOauth[]|ArrayCollection
     */
    public function getOauths()
    {
        return $this->oauths;
    }

    /**
     * @param UserOauth[]|ArrayCollection $oauths
     *
     * @return $this
     */
    public function setOauths($oauths): self
    {
        $this->oauths = $oauths;

        return $this;
    }

    public function getOauthByProvider(string $provider): ?UserOauth
    {
        foreach ($this->oauths as $oauth) {
            if ($oauth->getProvider() == $provider) {
                return $oauth;
            }
        }

        throw new \Exception("Провайдер $provider не найден");
    }

    public function getVkIdentifier(): int
    {
        return (int) $this->getOauthByProvider('vkontakte')->getIdentifier();
    }

    public function getGroupNames(): array
    {
        $names = [];
        foreach ($this->getGroups() as $group) {
            $names[$group->getId()] = $group->getName();
        }

        return $names;
    }

    /**
     * @return UserGroup[]|ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }

    public function hasGroup(string $name): bool
    {
        return in_array($name, $this->getGroupNames());
    }

    public function addGroup(UserGroup $group): self
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    public function removeGroup(UserGroup $group): self
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }

    public function getBasketAmount(): int
    {
        $amount = 0;

        foreach ($this->baskets as $basket) {
            $amount += $basket->getProductVariant()->getPrice() * $basket->getQuantity();
        }

        return $amount;
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

    /**
     * @return Order[]|ArrayCollection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Order[]|ArrayCollection $orders
     *
     * @return $this
     */
    public function setOrders($orders): self
    {
        $this->orders = $orders;

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

    public function getPurposeGive(): ?string
    {
        return $this->purpose_give;
    }

    public function setPurposeGive(?string $purpose_give): self
    {
        $this->purpose_give = $purpose_give;

        return $this;
    }

    public function getPurposeTake(): ?string
    {
        return $this->purpose_take;
    }

    public function setPurposeTake(?string $purpose_take): self
    {
        $this->purpose_take = $purpose_take;

        return $this;
    }

    public function getWorksheet(): ?Worksheet
    {
        if (empty($this->worksheet)) {
            return new Worksheet();
        }

        return unserialize($this->worksheet);
    }

    public function setWorksheet(?Worksheet $worksheet): self
    {
        $this->worksheet = serialize($worksheet);

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSocialLinks(): ?string
    {
        return $this->social_links;
    }

    public function setSocialLinks(?string $social_links): self
    {
        $this->social_links = $social_links;

        return $this;
    }

    public function getEducation(): ?string
    {
        return $this->education;
    }

    public function setEducation(?string $education): self
    {
        $this->education = $education;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getSchools(): ?string
    {
        return $this->schools;
    }

    public function setSchools(?string $schools): self
    {
        $this->schools = $schools;

        return $this;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity(?string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getParticipate(): ?string
    {
        return $this->participate;
    }

    public function setParticipate(?string $participate): self
    {
        $this->participate = $participate;

        return $this;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setSkills(?string $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    public function hasRelative(): bool
    {
        return $this->has_relative;
    }

    public function getHasRelative(): bool
    {
        return $this->has_relative;
    }

    public function setHasRelative(bool $has_relative): self
    {
        $this->has_relative = $has_relative;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Payment[]|Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param Payment[]|Collection $payments
     *
     * @return $this
     */
    public function setPayments($payments): self
    {
        $this->payments = $payments;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return User[]|Collection
     */
    public function getInvitedUsers(): Collection
    {
        return $this->invited_users;
    }

    /**
     * @param User[]|Collection $invited_users
     *
     * @return $this
     */
    public function setInvitedUsers(Collection $invited_users): self
    {
        $this->invited_users = $invited_users;

        return $this;
    }

    public function getInvitedByUser(): ?User
    {
        return $this->invited_by_user;
    }

    public function setInvitedByUser(?User $invited_by_user): self
    {
        $this->invited_by_user = $invited_by_user;

        return $this;
    }

    public function getInvite(): ?Invite
    {
        return $this->invite;
    }

    public function setInvite(?Invite $invite): self
    {
        $this->invite = $invite;

        return $this;
    }
}
