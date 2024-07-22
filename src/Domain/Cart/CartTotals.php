<?php

declare(strict_types=1);

namespace App\Domain\Cart;

class CartTotals
{
    public int $totalPrice = 0;
    public int $discountValue = 0;

    public function getTotalPriceAfterDiscount(): int
    {
        return $this->totalPrice - $this->discountValue;
    }
}