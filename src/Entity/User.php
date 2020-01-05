<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @Assert\Email(
     *      checkHost = false,
     *      checkMX = false,
     *      strict = true
     * )
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
     */
    protected $firstname;

    /**
     * Фамилия
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull(message="This value is not valid.")
     */
    protected $lastname;

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
     */
    protected $telegram_username;

    /**
     * @ORM\ManyToMany(targetEntity="UserGroup")
     * @ORM\JoinTable(name="users_groups_relations")
     */
    protected $groups;

    /**
     * @var UserOauth[]|ArrayCollection
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
}
