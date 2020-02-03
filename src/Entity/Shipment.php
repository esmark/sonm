<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\StatusTrait;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * Доставки
 *
 * @ORM\Entity()
 * @ORM\Table(name="shipments",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"status"}),
 *      }
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Shipment
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use StatusTrait;

    /**
     * Shipment constructor.
     */
    public function __construct()
    {
        $this->created_at   = new \DateTime();
    }
}
