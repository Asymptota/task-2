<?php

declare(strict_types=1);

namespace App\Application\Cart\Command;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class RemoveProductFromCart
{
    /**
     * @param string $uuid
     */
    public function __construct(private string $uuid)
    {

    }

    /**
     * @return UuidInterface
     */
    public function uuid(): UuidInterface
    {
        return Uuid::fromString($this->uuid);
    }
}