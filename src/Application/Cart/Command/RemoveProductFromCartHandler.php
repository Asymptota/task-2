<?php

declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\CartManager;
use App\Application\Factory\CartItemFactory;
use App\Domain\Cart\Cart;
use App\Infrastructure\Cart\CartUuidProvider;
use App\Infrastructure\Repository\CartsRepository;

readonly class RemoveProductFromCartHandler
{
    /**
     * @param CartUuidProvider $cartUuid
     * @param CartsRepository $carts
     * @param CartItemFactory $cartItemFactory
     * @param CartManager $cartManager
     */
    public function __construct(
        private CartUuidProvider $cartUuid,
        private CartsRepository $carts,
        private CartItemFactory $cartItemFactory,
        private CartManager $cartManager
    )
    {

    }

    /**
     * @param RemoveProductFromCart $removeProductFromCart
     * @return Cart
     */
    public function handle(RemoveProductFromCart $removeProductFromCart): Cart
    {
        $cart = $this->carts->getByUuid($this->cartUuid->getCartUuid());

        $cartItem = $this->cartItemFactory->createFromRemoveProductFromCart($removeProductFromCart);

        $this->cartManager->removeItemFromCart($cart, $cartItem);

        $this->carts->save($cart);

        return $cart;
    }
}