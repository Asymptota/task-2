<?php

declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\CartManager;
use App\Domain\Cart\Cart;
use App\Infrastructure\Cart\CartUuidProvider;
use App\Infrastructure\Repository\CartsRepository;

readonly class ClearCartHandler
{
    /**
     * @param CartUuidProvider $cartUuid
     * @param CartsRepository $carts
     * @param CartManager $cartManager
     */
    public function __construct(
        private CartUuidProvider $cartUuid,
        private CartsRepository $carts,
        private CartManager $cartManager
    )
    {

    }

    /**
     * @param ClearCart $clearCart
     * @return Cart
     */
    public function handle(ClearCart $clearCart): Cart
    {
        $cart = $this->carts->getByUuid($this->cartUuid->getCartUuid());

        $this->cartManager->clearCart($cart);

        $this->carts->save($cart);

        return $cart;
    }
}