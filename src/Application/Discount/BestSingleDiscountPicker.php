<?php

declare(strict_types=1);

namespace App\Application\Discount;

use App\Domain\Cart\Cart;

final readonly class BestSingleDiscountPicker implements Discounter
{
    /**
     * @param Discount[] $availableDiscounts
     */
    public function __construct(private iterable $availableDiscounts)
    {

    }

    /**
     * @param Cart $cart
     * @return void
     */
    public function apply(Cart $cart): void
    {
        $cart->clearDiscounts();
        $this->applySingleBestApplicableDiscount($cart);
    }

    /**
     * @param Cart $cart
     * @return void
     */
    private function applySingleBestApplicableDiscount(Cart $cart): void
    {
        $applicableBestDiscount = null;
        foreach ($this->availableDiscounts as $availableDiscount) {
            $discountIfApplicable = $availableDiscount->getDiscountIfApplicable($cart);

            if (!$applicableBestDiscount && $discountIfApplicable) {
                $applicableBestDiscount = $discountIfApplicable;
                continue;
            }

            if ($applicableBestDiscount && $discountIfApplicable) {
                $applicableBestDiscount = \App\Domain\Discount\Discount::compareDiscountsAndReturnBetter($discountIfApplicable, $applicableBestDiscount);
            }
        }

        if ($applicableBestDiscount) {
            $cart->addDiscount($applicableBestDiscount);
        }
    }
}