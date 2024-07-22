<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Exception;

class CartNotFoundException extends Exception
{
    public static function create(string $uuid): self
    {
        return new self(sprintf('Cart with Uuid `%s` not found.', $uuid));
    }
}