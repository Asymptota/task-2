<?php

declare(strict_types=1);

namespace App\Tests\App\Domain\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;
use App\Domain\Discount\Discount;
use App\Domain\Product\Product;
use App\Domain\Product\ProductType;
use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CartTest extends TestCase
{
    public function test_instantiated_cart_is_empty(): void
    {
        $cart = new Cart(Uuid::uuid4());


        $this->assertEmpty($cart->getCartItems());
    }

    public function test_instantiated_cart_has_no_discounts(): void
    {
        $cart = new Cart(Uuid::uuid4());


        $this->assertEmpty($cart->getDiscounts());
    }

    public function test_instantiated_cart_has_totals_equal_zero(): void
    {
        $cart = new Cart(Uuid::uuid4());


        $this->assertSame(0, $cart->getTotalPrice());
        $this->assertSame(0, $cart->getFinalTotalPrice());
        $this->assertSame(0, $cart->getTotalDiscount());
    }

    public function test_cart_has_item_after_adding_item_to_cart(): void
    {
        $product = new Product(ProductType::BREAD, 500, 'Chleb');

        $cartItemUuid = Uuid::uuid4();
        $cartItem = $this->fakeCartItemFactory($product, 2, $cartItemUuid);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem);


        $this->assertCount(1, $cart->getCartItems());
        $actualCartItem = $cart->getCartItems()[$cartItemUuid->toString()];
        $this->assertSame($cartItem, $actualCartItem);
        $this->assertSame($cartItem->getProductName(), $actualCartItem->getProductName());
    }

    public function test_cart_has_non_zero_total_price_after_adding_item_to_cart(): void
    {
        $productPrice = 500;
        $product = new Product(ProductType::BREAD, $productPrice, 'Chleb');

        $cartItemUuid = Uuid::uuid4();
        $cartItemQuantity = 2;
        $cartItem = $this->fakeCartItemFactory($product, $cartItemQuantity, $cartItemUuid);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem);


        $this->assertSame($productPrice * $cartItemQuantity, $cart->getTotalPrice());
        $this->assertSame($productPrice * $cartItemQuantity, $cart->getFinalTotalPrice());
        $this->assertSame(0, $cart->getTotalDiscount());
    }

    public function test_cart_with_items_is_empty_after_clearing_cart(): void
    {
        $cartItem1 = $this->fakeCartItemFactory(
            new Product(ProductType::BREAD, 500, 'Chleb'),
            1
        );
        $cartItem2 = $this->fakeCartItemFactory(
            new Product(ProductType::CANDY, 1000, 'Cukierek'),
            2
        );

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);

        $cart->clearCart();

        $this->assertEmpty($cart->getCartItems());
    }

    public function test_cart_with_items_has_totals_equals_zero_after_clearing_cart(): void
    {
        $cartItem1 = $this->fakeCartItemFactory(
            new Product(ProductType::BREAD, 500, 'Chleb'),
            1
        );
        $cartItem2 = $this->fakeCartItemFactory(
            new Product(ProductType::CANDY, 1000, 'Cukierek'),
            2
        );

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);

        $cart->clearCart();

        $this->assertSame(0, $cart->getTotalPrice());
        $this->assertSame(0, $cart->getFinalTotalPrice());
        $this->assertSame(0, $cart->getTotalDiscount());
    }

    public function test_adding_discount_to_an_empty_cart_results_in_exception(): void
    {
        $cart = new Cart(Uuid::uuid4());

        $discount = new Discount('fake_discount', 20);


        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Cannot add discount to an empty cart.');

        $cart->addDiscount($discount);
    }

    public function test_cart_with_items_and_discount_should_have_total_discount_non_zero_value(): void
    {
        $cartItem1 = $this->fakeCartItemFactory(
            new Product(ProductType::BREAD, 500, 'Chleb'),
            5
        );

        $cartItem2 = $this->fakeCartItemFactory(
            new Product(ProductType::CANDY, 1000, 'Cukierek'),
            2
        );

        $discountAmount = 40;
        $discount = new Discount('fake_discount', $discountAmount);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);
        $cart->addDiscount($discount);


        $this->assertSame($discountAmount, $cart->getTotalDiscount());
    }

    public function test_cart_with_items_and_discount_should_have_total_and_total_final_price_different_by_total_discount(): void
    {
        $firstProductPrice = 500;
        $firstCartItemQuantity = 5;
        $cartItem1 = $this->fakeCartItemFactory(
            new Product(ProductType::BREAD, $firstProductPrice, 'Chleb'),
            $firstCartItemQuantity
        );

        $secondProductPrice = 1000;
        $secondCartItemQuantity = 2;
        $cartItem2 = $this->fakeCartItemFactory(
            new Product(ProductType::CANDY, $secondProductPrice, 'Cukierek'),
            $secondCartItemQuantity
        );

        $discountAmount = 40;
        $discount = new Discount('fake_discount', $discountAmount);

        $cart = new Cart(Uuid::uuid4());
        $cart->addCartItem($cartItem1);
        $cart->addCartItem($cartItem2);
        $cart->addDiscount($discount);


        $expectedCartTotal = ($firstProductPrice * $firstCartItemQuantity) + ($secondProductPrice * $secondCartItemQuantity);
        $this->assertSame($expectedCartTotal, $cart->getTotalPrice());
        $this->assertSame($expectedCartTotal - $discountAmount, $cart->getFinalTotalPrice());
    }

    //TODO: test clearDiscounts
    //TODO: test removeCartItem
    //TODO: test updateCartItemQuantity
    //TODO: test clearDiscounts

    private function fakeCartItemFactory(Product $product, int $quantity, ?UuidInterface $uuid = null): CartItem
    {
        $cartItem = new CartItem($uuid);
        $cartItem->setProduct($product);
        $cartItem->setQuantity($quantity);

        return $cartItem;
    }
}