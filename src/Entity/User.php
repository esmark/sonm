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
 * @UniqueEntity(fields="username", message="Username is already exists")
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
     * @ORM\Column(type="string", length=40, unique=true)
     * @Assert\NotNull(message="This value is not valid.")
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=190)
     * @Assert\Length(min = 6, minMessage = "Password length must be at least {{ limit }} characters long")
     */
    protected $password;

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
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    protected $latitude;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=11, scale=8, nullable=true)
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
     * User constructor.
     */
    public function __construct()
    {
        $this->created_at       = new \DateTime();
        $this->is_enabled       = true;
        $this->password         = '';
        $this->roles            = [];
        $this->username         = '';
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
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        // add $this->salt too if you don't use Bcrypt or Argon2i
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
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
        // $this->plainPassword = null;
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
        $this->username = $username;

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
     * {@inheritdoc}
     */
    public function addRole($role)
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
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
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
}
