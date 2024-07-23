<?php

declare(strict_types=1);

namespace App\Application\Discount;

use App\Domain\Cart\Cart;

final class EveryFifthSameTypeProductFree implements Discount
{
    private const string NAME = 'every_fifth_same_type_product_free';

    private const int THRESHOLD = 5;

    /**
     * @param Cart $cart
     * @return \App\Domain\Discount\Discount|null
     */
    public function getDiscountIfApplicable(Cart $cart): ?\App\Domain\Discount\Discount
    {
        $totalDiscount = 0;

        foreach ($cart->getCartItems() as $cartItem) {
            $numberOfFifthItemsOfEachType = intdiv($cartItem->getQuantity(), $this::THRESHOLD);
            $totalDiscount +=  $numberOfFifthItemsOfEachType * $cartItem->getPrice();
        }

        return $totalDiscount <= $cart->getTotalDiscountedValue()
            ? null
            : new \App\Domain\Discount\Discount($this::NAME, $totalDiscount);
    }
}