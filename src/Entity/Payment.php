<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Платежи
 *
 * @ORM\Entity()
 * @ORM\Table(name="payments",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *      }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Payment
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use StatusTrait;

    /**
     * Payment constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
    }
}
