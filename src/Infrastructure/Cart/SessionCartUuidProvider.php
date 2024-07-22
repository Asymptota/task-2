<?php

declare(strict_types=1);

namespace App\Infrastructure\Cart;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SessionCartUuidProvider implements CartUuidProvider
{

    public function getCartUuid(bool $recreate = false): UuidInterface
    {
        // TODO: providing cart uuid from session or set new one in session
        // TODO: log if cart uuid not found in session for debug purpose

        return Uuid::uuid4();
    }
}