<?php

declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\CartManager;
use App\Domain\Cart\Cart;
use App\Infrastructure\Cart\CartUuidProvider;
use App\Infrastructure\Repository\CartsRepository;

readonly class EditProductInCartQuantityHandler
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
     * @param EditProductInCartQuantity $editProductInCartQuantity
     * @return Cart
     */
    public function handle(EditProductInCartQuantity $editProductInCartQuantity): Cart
    {
        $cart = $this->carts->getByUuid($this->cartUuid->getCartUuid());

        $cartItem = $cart->getCartItemByUuid($editProductInCartQuantity->uuid());
        $cartItem->setQuantity($editProductInCartQuantity->quantity());

        $this->cartManager->editItemInCart($cart, $cartItem);

        $this->carts->save($cart);

        return $cart;
    }
}