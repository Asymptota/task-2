<?php

declare(strict_types=1);

namespace App\Application\Discount;

use App\Domain\Cart\Cart;

interface Discounter
{
    public function apply(Cart $cart): void;
}