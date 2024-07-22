<?php

declare(strict_types=1);

namespace App\Application\Discount;

use App\Domain\Cart\Cart;

final class PercentageDiscountOverThreshold implements Discount
{
    private const string NAME = 'percentage_discount_over_1000_threshold';

    private const int THRESHOLD = 1000;

    private const int PERCENTAGE_DISCOUNT = 10;

    /**
     * @param Cart $cart
     * @return \App\Domain\Discount\Discount|null
     */
    public function getDiscountIfApplicable(Cart $cart): ?\App\Domain\Discount\Discount
    {
        $cartTotalPrice = $cart->getTotalPrice();
        if ($cartTotalPrice <= $this::THRESHOLD) {
            return null;
        }

        $discountValue = (int) ($cartTotalPrice * ($this::PERCENTAGE_DISCOUNT / 100));

        return $discountValue <= $cart->getTotalDiscount()
            ? null
            : new \App\Domain\Discount\Discount($this::NAME, $discountValue);
    }
}