<?php

declare(strict_types=1);

namespace App\Application\Exception;

use Exception;

class ProductNotFoundException extends Exception
{
    public static function create(string $uuid): self
    {
        return new self(sprintf('Product with Uuid `%s` not found.', $uuid));
    }
}