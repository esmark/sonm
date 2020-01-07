<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity()
 * @ORM\Table("cooperatives_history",
 *      indexes={
 *          @ORM\Index(columns={"action"}),
 *          @ORM\Index(columns={"created_at"}),
 *      },
 * )
 */
class CooperativeHistory
{
    use ColumnTrait\Id;
    use ColumnTrait\User;
    use ColumnTrait\CreatedAt;

    const ACTION_CREATE               = 0;
    const ACTION_UPDATE               = 1;

    const ACTION_ASSURANCE_SUCCESS    = 20;
    const ACTION_ASSURANCE_DECLINE    = 21;

    const ACTION_MEMBER_ADD           = 50;
    const ACTION_MEMBER_REMOVE        = 51;
    const ACTION_MEMBER_STATUS        = 52;
    const ACTION_MEMBER_REQUEST       = 53;
    const ACTION_MEMBER_DECLINE       = 54;

    static protected $action_values = [
        self::ACTION_CREATE               => 'Создание кооператива',
        self::ACTION_UPDATE               => 'Обновление данных',
        self::ACTION_ASSURANCE_SUCCESS    => 'Успешное заверение',
        self::ACTION_ASSURANCE_DECLINE    => 'Заверение отклонено',
        self::ACTION_MEMBER_ADD           => 'Добавление участника',
        self::ACTION_MEMBER_REMOVE        => 'Удаление участника',
        self::ACTION_MEMBER_STATUS        => 'Изменение статуса участника',
        self::ACTION_MEMBER_REQUEST       => 'Заявка на вступление',
        self::ACTION_MEMBER_DECLINE       => 'Заявка на вступление отклонена',
    ];

    /**
     * @var array|null
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $new_value;

    /**
     * @var array|null
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $old_value;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $action;

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
        $this->action       = self::ACTION_CREATE;
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
     * @return int
     */
    public function getActionAsText(): string
    {
        if (isset(self::$action_values[$this->action])) {
            return self::$action_values[$this->action];
        }

        return 'N/A';
    }

    /**
     * @return int
     */
    public function getAction(): int
    {
        return $this->action;
    }

    /**
     * @param int $action
     *
     * @return $this
     */
    public function setAction(int $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getNewValue(): ?array
    {
        return $this->new_value;
    }

    /**
     * @param array|null $new_value
     *
     * @return $this
     */
    public function setNewValue(?array $new_value): self
    {
        $this->new_value = $new_value;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOldValue(): ?array
    {
        return $this->old_value;
    }

    /**
     * @param array|null $old_value
     *
     * @return $this
     */
    public function setOldValue(?array $old_value): self
    {
        $this->old_value = $old_value;

        return $this;
    }
}
