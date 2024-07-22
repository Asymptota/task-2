<?php

declare(strict_types=1);

namespace App\Tests\App\Domain\Cart;

use App\Domain\Cart\CartTotals;
use PHPUnit\Framework\TestCase;

class CartTotalsTest extends TestCase
{
    public function test_get_total_price_after_discount(): void
    {
        $cartTotals = new CartTotals();
        $cartTotals->totalPrice = 1000;
        $cartTotals->discountValue = 500;

        $expectedTotalPriceAfterDiscount = 500;


        $this->assertSame($expectedTotalPriceAfterDiscount, $cartTotals->getTotalPriceAfterDiscount());
    }
}