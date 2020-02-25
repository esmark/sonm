<?php

declare(strict_types=1);

namespace App\Shipping;

use App\Entity\Order;

interface ShippingInterface
{
//    public const STATUS_CART      = 'new';
//    public const STATUS_READY     = 'ready';
//    public const STATUS_SHIPPED   = 'shipped';
//    public const STATUS_CANCELLED = 'cancelled';

    public function getPrice(): int;

    public function getTitle(): string;

    public function handleOrder(Order $order);
}
