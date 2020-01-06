<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table("cooperatives_history",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"type"}),
 *      },
 * )
 */
class CooperativeHistory
{
    use ColumnTrait\Id;
    use ColumnTrait\Comment;
    use ColumnTrait\User;
    use ColumnTrait\CreatedAt;

    const TYPE_CREATE               = 0;  // Создание кооператива
    const TYPE_UPDATE               = 1;  // Создание кооператива

    const TYPE_ASSURANCE_SUCCESS    = 20; // Успешное заверение
    const TYPE_ASSURANCE_DECLINE    = 21; // Заверение отклонено

    const TYPE_MEMBER_ADD           = 50; // Добавление участника
    const TYPE_MEMBER_REMOVE        = 51; // Удаление участника
    const TYPE_MEMBER_STATUS        = 52; // Изменение статуса участника

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $new_value;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $old_value;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @var Cooperative
     *
     * @ORM\ManyToOne(targetEntity="Cooperative", inversedBy="history", cascade={"persist"})
     */
    protected $cooperative;

    /**
     * CooperativeHistory constructor.
     */
    public function __construct(?Cooperative $cooperative = null)
    {
        $this->created_at   = new \DateTime();
        $this->cooperative  = $cooperative;
        $this->type         = self::TYPE_CREATE;
    }

    /**
     * @return Cooperative
     */
    public function getCooperative(): Cooperative
    {
        return $this->cooperative;
    }

    /**
     * @param Cooperative $cooperative
     *
     * @return $this
     */
    public function setCooperative(Cooperative $cooperative): self
    {
        $this->cooperative = $cooperative;

        return $this;
    }

    /**
     * @return array
     */
    public function getOldValue(): array
    {
        return $this->old_value;
    }

    /**
     * @param array $old_value
     *
     * @return $this
     */
    public function setOldValue(array $old_value): self
    {
        $this->old_value = $old_value;

        return $this;
    }

    /**
     * @return array
     */
    public function getNewValue(): array
    {
        return $this->new_value;
    }

    /**
     * @param array $new_value
     *
     * @return $this
     */
    public function setNewValue(array $new_value): self
    {
        $this->new_value = $new_value;

        return $this;
    }
}
