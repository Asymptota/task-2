<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Application\Cart\Command\AddProductToCart;
use App\Application\Cart\Command\RemoveProductFromCart;
use App\Domain\Cart\CartItem;
use App\Infrastructure\Repository\ProductsRepository;

readonly class CartItemFactory
{
    public function __construct(private ProductsRepository $products)
    {
    }

    public function createFromAddProductToCart(AddProductToCart $addProductToCart): CartItem
    {
        $product = $this->products->getByUuid($addProductToCart->uuid());

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity($addProductToCart->quantity());

        return $cartItem;
    }

    public function createFromRemoveProductFromCart(RemoveProductFromCart $removeProductFromCart): CartItem
    {
        return new CartItem($removeProductFromCart->uuid());
    }
}