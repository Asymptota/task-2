<?php

declare(strict_types=1);

namespace App\Application\Discount;

use App\Domain\Cart\Cart;

interface Discount
{
    public function getDiscountIfApplicable(Cart $cart): ?\App\Domain\Discount\Discount;
}