<?php

declare(strict_types=1);

namespace App\Application\Cart\Command;

use App\Application\Cart\CartManager;
use App\Application\Factory\CartItemFactory;
use App\Domain\Cart\Cart;
use App\Infrastructure\Cart\CartUuidProvider;
use App\Infrastructure\Repository\CartsRepository;

final readonly class AddProductToCartHandler
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
     * @param AddProductToCart $addProduct
     * @return Cart
     */
    public function handle(AddProductToCart $addProduct): Cart
    {
        $cart = $this->carts->findByUuid($this->cartUuid->getCartUuid());
        if (!$cart) {
            $cart = new Cart($this->cartUuid->getCartUuid());
        }

        $this->cartManager->addItemToCart($cart, $this->cartItemFactory->createFromAddProductToCart($addProduct));

        $this->carts->save($cart);

        return $cart;
    }
}