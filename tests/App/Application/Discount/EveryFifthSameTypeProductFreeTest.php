<?php

declare(strict_types=1);

namespace App\Tests\App\Application\Discount;

use App\Application\Discount\EveryFifthSameTypeProductFree;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Discount\Discount;
use App\Domain\Product\Product;
use App\Domain\Product\ProductType;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class EveryFifthSameTypeProductFreeTest extends TestCase
{
    public function test_cart_does_not_fulfill_discount_rule(): void
    {
        $everyFifthSameTypeProductFree = new EveryFifthSameTypeProductFree();

        $product = new Product(ProductType::DRINK, 2000, 'Pepsi');

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(4);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem);


        $result = $everyFifthSameTypeProductFree->getDiscountIfApplicable($cart);

        $this->assertNull($result);
    }

    public function test_cart_does_fulfill_discount_rule(): void
    {
        $everyFifthSameTypeProductFree = new EveryFifthSameTypeProductFree();

        $cartItem1 = new CartItem();
        $cartItem1->setProduct(new Product(ProductType::DRINK, 2000, 'Pepsi'));
        $cartItem1->setQuantity(6);

        $cartItem2 = new CartItem();
        $cartItem2->setProduct(new Product(ProductType::CANDY, 1000, 'Cukierek'));
        $cartItem2->setQuantity(5);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);


        $discount = $everyFifthSameTypeProductFree->getDiscountIfApplicable($cart);

        $this->assertInstanceOf(Discount::class, $discount);
    }

    public function test_cart_does_fulfill_discount_rule_and_discount_value_matches(): void
    {
        $everyFifthSameTypeProductFree = new EveryFifthSameTypeProductFree();

        $cartItem1 = new CartItem();
        $cartItem1->setProduct(new Product(ProductType::DRINK, 2000, 'Pepsi'));
        $cartItem1->setQuantity(6);

        $cartItem2 = new CartItem();
        $cartItem2->setProduct(new Product(ProductType::CANDY, 1000, 'Cukierek'));
        $cartItem2->setQuantity(5);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);


        $discount = $everyFifthSameTypeProductFree->getDiscountIfApplicable($cart);

        $expectedDiscountAmount = 1 * 2000 + 1 * 1000;
        $this->assertSame($expectedDiscountAmount, $discount->getAmount());
    }
}