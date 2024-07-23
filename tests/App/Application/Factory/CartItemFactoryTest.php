<?php

declare(strict_types=1);

namespace App\Tests\App\Application\Factory;

use App\Application\Cart\Command\AddProductToCart;
use App\Application\Cart\Command\RemoveProductFromCart;
use App\Application\Factory\CartItemFactory;
use App\Domain\Cart\CartItem;
use App\Domain\Product\Product;
use App\Domain\Product\ProductType;
use App\Infrastructure\Repository\InMemory\Products;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CartItemFactoryTest extends TestCase
{
    public function test_cart_item_created_from_add_product_to_cart(): void
    {
        $expectedProduct = new Product(ProductType::DRINK, 1000, 'Water');

        $productsRepositoryMock = $this->createMock(Products::class);
        $productsRepositoryMock->expects($this->once())
            ->method('getByUuid')
            ->with($expectedProduct->getUuid())
            ->willReturn($expectedProduct);

        $expectedQuantity = 5;
        $addProductToCart = new AddProductToCart($expectedProduct->getUuid()->toString(), $expectedQuantity);
        $factory = new CartItemFactory($productsRepositoryMock);
        $cartItem = $factory->createFromAddProductToCart($addProductToCart);

        $this->assertSame($expectedProduct->getName(), $cartItem->getProductName());
        $this->assertSame($expectedProduct->getPrice(), $cartItem->getPrice());
        $this->assertSame($expectedQuantity, $cartItem->getQuantity());
    }

    public function test_cart_item_created_from_remove_product_from_cart(): void
    {
        $expectedUuid = Uuid::uuid4();
        $productsRepositoryMock = $this->createMock(Products::class);

        $removeProductFromCart = new RemoveProductFromCart($expectedUuid->toString());
        $factory = new CartItemFactory($productsRepositoryMock);
        $cartItem = $factory->createFromRemoveProductFromCart($removeProductFromCart);

        $this->assertSame($expectedUuid->toString(), $cartItem->getUuid()->toString());
    }
}