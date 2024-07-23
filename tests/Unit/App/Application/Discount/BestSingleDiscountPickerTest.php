<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\Application\Discount;

use App\Application\Discount\BestSingleDiscountPicker;
use App\Application\Discount\EveryFifthSameTypeProductFree;
use App\Application\Discount\PercentageDiscountOverThreshold;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Product\Product;
use App\Domain\Product\ProductType;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class BestSingleDiscountPickerTest extends TestCase
{
    public function test_no_discount_picked(): void
    {
        $bestSingleDiscountPicker = $this->bestSingleDiscountPickerProvider();

        $cartItem = new CartItem();
        $cartItem->setProduct(new Product(ProductType::BREAD, 100, 'Chleb'));
        $cartItem->setQuantity(3);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem);


        $bestSingleDiscountPicker->apply($cart);


        $this->assertEmpty($cart->getDiscounts());
    }

    public function test_every_fifth_same_type_product_free_discount_picked(): void
    {
        $bestSingleDiscountPicker = $this->bestSingleDiscountPickerProvider();

        $cartItem = new CartItem();
        $cartItem->setProduct(new Product(ProductType::BREAD, 100, 'Chleb'));
        $cartItem->setQuantity(11);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem);


        $bestSingleDiscountPicker->apply($cart);


        $this->assertNotEmpty($cart->getDiscounts());
        $firstKey = array_keys($cart->getDiscounts())[0];
        $this->assertSame('every_fifth_same_type_product_free', $cart->getDiscounts()[$firstKey]->getName());
    }

    public function test_percentage_discount_over_threshold_discount_picked(): void
    {
        $bestSingleDiscountPicker = $this->bestSingleDiscountPickerProvider();

        $cartItem = new CartItem();
        $cartItem->setProduct(new Product(ProductType::BREAD, 1000, 'Chleb'));
        $cartItem->setQuantity(4);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem);


        $bestSingleDiscountPicker->apply($cart);


        $this->assertNotEmpty($cart->getDiscounts());
        $firstKey = array_keys($cart->getDiscounts())[0];
        $this->assertSame('percentage_discount_over_1000_threshold', $cart->getDiscounts()[$firstKey]->getName());
    }

    public function test_percentage_discount_over_threshold_discount_picked_as_better_discount(): void
    {
        $bestSingleDiscountPicker = $this->bestSingleDiscountPickerProvider();

        $cartItem1 = new CartItem();
        $cartItem1->setProduct(new Product(ProductType::BREAD, 1000, 'Chleb'));
        $cartItem1->setQuantity(6);

        $cartItem2 = new CartItem();
        $cartItem2->setProduct(new Product(ProductType::CANDY, 4000, 'Cukierek'));
        $cartItem2->setQuantity(4);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);


        $bestSingleDiscountPicker->apply($cart);


        $this->assertNotEmpty($cart->getDiscounts());
        $firstKey = array_keys($cart->getDiscounts())[0];
        $this->assertSame('percentage_discount_over_1000_threshold', $cart->getDiscounts()[$firstKey]->getName());
    }

    private function bestSingleDiscountPickerProvider(): BestSingleDiscountPicker
    {
        $availableDiscounts = [
            new EveryFifthSameTypeProductFree(),
            new PercentageDiscountOverThreshold()
        ];

        return new BestSingleDiscountPicker($availableDiscounts);
    }
}