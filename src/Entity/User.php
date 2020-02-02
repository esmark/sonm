<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Worksheet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
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
class User implements UserInterface
{
    use ColumnTrait\Uuid;
    use ColumnTrait\EmailUnique;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\IsEnabled;

    const ROLE_DEFAULT     = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(min = 3, minMessage = "Username length must be at least {{ limit }} characters long")
     * @Assert\NotNull(message="This value is not valid.")
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40, unique=true)
     */
    protected $username_canonical;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Email(mode="strict")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $email_canonical;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=190)
     * @Assert\Length(min = 6, minMessage = "Password length must be at least {{ limit }} characters long")
     */
    protected $password;

    /**
     * @var string|null
     *
     * Plain password. Used for model validation. Must not be persisted.
     */
    protected $plain_password;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $roles;

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
     * @Assert\NotNull(message="This value is not valid.")
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
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $last_login;

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

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
        $this->is_enabled   = true;
        $this->email        = '';
        $this->baskets      = new ArrayCollection();
        $this->orders       = new ArrayCollection();
        $this->groups       = new ArrayCollection();
        $this->oauths       = new ArrayCollection();
        $this->password     = '';
        $this->roles        = [];
        $this->username     = '';
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (empty($this->getFirstname()) and empty($this->getLastname())) {
            return $this->getUsername();
        }

        return $this->getFirstname().' '.$this->getLastname();
    }

    /**
     * Является ли юзер действующим участником какого-либо кооператива?
     *
     * @return bool
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

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->is_enabled,
            $this->email,
            $this->email_canonical,
            $this->username,
            $this->username_canonical,
            $this->password
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->is_enabled,
            $this->email,
            $this->email_canonical,
            $this->username,
            $this->username_canonical,
            $this->password
        ] = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @param string $string
     *
     * @return string|null
     */
    static public function canonicalize(string $string): ?string
    {
        if (null === $string) {
            return null;
        }

        $encoding = mb_detect_encoding($string);
        $result = $encoding
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : mb_convert_case($string, MB_CASE_LOWER);

        return $result;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        $this->plain_password = null;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    /**
     * @param bool $boolean
     *
     * @return $this
     */
    public function setSuperAdmin(bool $boolean): self
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = trim($username);
        $this->username_canonical = self::canonicalize($this->username);

        return $this;
    }

    /**
     * @return string
     */
    public function getUsernameCanonical(): string
    {
        return $this->username_canonical;
    }

    /**
     * @param string $username_canonical
     *
     * @return $this
     */
    public function setUsernameCanonical(string $username_canonical): self
    {
        $this->username_canonical = $username_canonical;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    /**
     * @param string|null $plain_password
     *
     * @return $this
     */
    public function setPlainPassword(?string $plain_password): self
    {
        $this->plain_password = $plain_password;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function addRole(string $role): self
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function removeRole(string $role): self
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     *
     * @return $this
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     *
     * @return $this
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->last_login;
    }

    /**
     * @param \DateTime|null $last_login
     *
     * @return $this
     */
    public function setLastLogin(?\DateTime $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTelegramUserId(): ?int
    {
        return $this->telegram_user_id;
    }

    /**
     * @param int|null $telegram_user_id
     *
     * @return $this
     */
    public function setTelegramUserId(?int $telegram_user_id): self
    {
        $this->telegram_user_id = $telegram_user_id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelegramUsername(): ?string
    {
        return $this->telegram_username;
    }

    /**
     * @param string|null $telegram_username
     *
     * @return $this
     */
    public function setTelegramUsername(?string $telegram_username): self
    {
        $this->telegram_username = $telegram_username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = trim($email);
        $this->email_canonical = self::canonicalize(trim($email));

        return $this;
    }

    /**
     * @return string
     */
    public function getEmailCanonical(): string
    {
        return $this->email_canonical;
    }

    /**
     * @param string $email_canonical
     *
     * @return $this
     */
    public function setEmailCanonical(string $email_canonical): self
    {
        $this->email_canonical = $email_canonical;

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

    /**
     * @param string $provider
     *
     * @return UserOauth|null
     */
    public function getOauthByProvider(string $provider): ?UserOauth
    {
        foreach ($this->oauths as $oauth) {
            if ($oauth->getProvider() == $provider) {
                return $oauth;
            }
        }

        throw new \Exception("Провайдер $provider не найден");
    }

    /**
     * @return int
     */
    public function getVkIdentifier(): int
    {
        return (int) $this->getOauthByProvider('vkontakte')->getIdentifier();
    }

    /**
     * @return array
     */
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

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasGroup(string $name): bool
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * @param UserGroup $group
     *
     * @return $this
     */
    public function addGroup(UserGroup $group): self
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * @param UserGroup $group
     *
     * @return $this
     */
    public function removeGroup(UserGroup $group): self
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }

    /**
     * @return int
     */
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

    /**
     * @return string|null
     */
    public function getPurposeGive(): ?string
    {
        return $this->purpose_give;
    }

    /**
     * @param string|null $purpose_give
     *
     * @return $this
     */
    public function setPurposeGive(?string $purpose_give): self
    {
        $this->purpose_give = $purpose_give;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPurposeTake(): ?string
    {
        return $this->purpose_take;
    }

    /**
     * @param string|null $purpose_take
     *
     * @return $this
     */
    public function setPurposeTake(?string $purpose_take): self
    {
        $this->purpose_take = $purpose_take;

        return $this;
    }

    /**
     * @return Worksheet|null
     */
    public function getWorksheet(): Worksheet
    {
        if (empty($this->worksheet)) {
            return new Worksheet();
        }

        return unserialize($this->worksheet);
    }

    /**
     * @param Worksheet|null $worksheet
     *
     * @return $this
     */
    public function setWorksheet(Worksheet $worksheet): self
    {
        $this->worksheet = serialize($worksheet);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    /**
     * @param string|null $patronymic
     *
     * @return $this
     */
    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     *
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSocialLinks(): ?string
    {
        return $this->social_links;
    }

    /**
     * @param string|null $social_links
     *
     * @return $this
     */
    public function setSocialLinks(?string $social_links): self
    {
        $this->social_links = $social_links;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEducation(): ?string
    {
        return $this->education;
    }

    /**
     * @param string|null $education
     *
     * @return $this
     */
    public function setEducation(?string $education): self
    {
        $this->education = $education;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     *
     * @return $this
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSchools(): ?string
    {
        return $this->schools;
    }

    /**
     * @param string|null $schools
     *
     * @return $this
     */
    public function setSchools(?string $schools): self
    {
        $this->schools = $schools;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getActivity(): ?string
    {
        return $this->activity;
    }

    /**
     * @param string|null $activity
     *
     * @return $this
     */
    public function setActivity(?string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParticipate(): ?string
    {
        return $this->participate;
    }

    /**
     * @param string|null $participate
     *
     * @return $this
     */
    public function setParticipate(?string $participate): self
    {
        $this->participate = $participate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSkills(): ?string
    {
        return $this->skills;
    }

    /**
     * @param string|null $skills
     *
     * @return $this
     */
    public function setSkills(?string $skills): self
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasRelative(): bool
    {
        return $this->has_relative;
    }

    /**
     * @return bool
     */
    public function getHasRelative(): bool
    {
        return $this->has_relative;
    }

    /**
     * @param bool $has_relative
     *
     * @return $this
     */
    public function setHasRelative(bool $has_relative): self
    {
        $this->has_relative = $has_relative;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     *
     * @return $this
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
