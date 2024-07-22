<?php

declare(strict_types=1);

namespace App\Application\Cart\Command;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class AddProductToCart
{
    /**
     * @param string $uuid
     * @param int $quantity
     */
    public function __construct(private string $uuid, private int $quantity)
    {

    }

    /**
     * @return UuidInterface
     */
    public function uuid(): UuidInterface
    {
        return Uuid::fromString($this->uuid);
    }

    /**
     * @return int
     */
    public function quantity(): int
    {
        return $this->quantity;
    }
}