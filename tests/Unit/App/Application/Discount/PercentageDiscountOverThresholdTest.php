<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\Application\Discount;

use App\Application\Discount\PercentageDiscountOverThreshold;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Discount\Discount;
use App\Domain\Product\Product;
use App\Domain\Product\ProductType;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PercentageDiscountOverThresholdTest extends TestCase
{
    private const int FIXED_PERCENTAGE_DISCOUNT = 10;

    public function test_cart_does_not_fulfill_discount_rule(): void
    {
        $percentageDiscountOverThreshold = new PercentageDiscountOverThreshold();

        $product = new Product(ProductType::DRINK, 200, 'Pepsi');

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(4);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem);


        $result = $percentageDiscountOverThreshold->getDiscountIfApplicable($cart);

        $this->assertNull($result);
    }

    public function test_cart_does_fulfill_discount_rule(): void
    {
        $percentageDiscountOverThreshold = new PercentageDiscountOverThreshold();

        $cartItem1 = new CartItem();
        $cartItem1->setProduct(new Product(ProductType::DRINK, 2000, 'Pepsi'));
        $cartItem1->setQuantity(2);

        $cartItem2 = new CartItem();
        $cartItem2->setProduct(new Product(ProductType::CANDY, 1000, 'Cukierek'));
        $cartItem2->setQuantity(3);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);


        $discount = $percentageDiscountOverThreshold->getDiscountIfApplicable($cart);

        $this->assertInstanceOf(Discount::class, $discount);
    }

    public function test_cart_does_fulfill_discount_rule_and_discount_value_matches(): void
    {
        $percentageDiscountOverThreshold = new PercentageDiscountOverThreshold();

        $product1Price = 2000;
        $cartItem1Quantity = 6;
        $cartItem1 = new CartItem();
        $cartItem1->setProduct(new Product(ProductType::DRINK, $product1Price, 'Pepsi'));
        $cartItem1->setQuantity($cartItem1Quantity);

        $product2Price = 1000;
        $cartItem2Quantity = 3;
        $cartItem2 = new CartItem();
        $cartItem2->setProduct(new Product(ProductType::CANDY, $product2Price, 'Cukierek'));
        $cartItem2->setQuantity($cartItem2Quantity);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);


        $discount = $percentageDiscountOverThreshold->getDiscountIfApplicable($cart);

        $expectedDiscountAmount = (int) (($cartItem1Quantity * $product1Price + $cartItem2Quantity * $product2Price) * (self::FIXED_PERCENTAGE_DISCOUNT / 100));
        $this->assertSame($expectedDiscountAmount, $discount->getAmount());
    }
}