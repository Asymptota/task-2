<?php

declare(strict_types=1);

namespace App\Infrastructure\Cart;

use Ramsey\Uuid\UuidInterface;

interface CartUuidProvider
{
    /**
     * @param bool $recreate
     * @return UuidInterface
     */
    public function getCartUuid(bool $recreate = false): UuidInterface;
}