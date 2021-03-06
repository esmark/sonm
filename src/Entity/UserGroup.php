<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table("users_groups",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"position"}),
 *          @ORM\Index(columns={"title"}),
 *      }
 * )
 */
class UserGroup
{
    use ColumnTrait\Id;
    use ColumnTrait\Name;
    use ColumnTrait\Position;
    use ColumnTrait\TitleNotBlank;
    use ColumnTrait\CreatedAt;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $roles;

    public function __construct(array $roles = [])
    {
        $this->created_at   = new \DateTime();
        $this->position     = 0;
        $this->roles        = $roles;
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
    }

    public function addRole(string $role): self
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    public function removeRole(string $role): self
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
