<?php

declare(strict_types=1);

namespace App\Application\Cart;

use App\Application\Discount\Discounter;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartItem;

readonly class CartManager
{
    public function __construct(private Discounter $discounter)
    {

    }

    /**
     * @param Cart $cart
     * @param CartItem $cartItem
     * @return void
     */
    public function addItemToCart(Cart $cart, CartItem $cartItem): void
    {
        $cart->addCartItem($cartItem);
        $this->manageCartAfterChange($cart);
    }

    public function editItemInCart(Cart $cart, CartItem $cartItem): void
    {
        $cart->updateCartItemQuantity($cartItem);
        $this->manageCartAfterChange($cart);
    }

    public function removeItemFromCart(Cart $cart, CartItem $cartItem): void
    {
        $cart->removeCartItem($cartItem);
        $this->manageCartAfterChange($cart);
    }

    public function clearCart(Cart $cart): void
    {
        $cart->clearCart();
        $this->manageCartAfterChange($cart);
    }

    /**
     * @param Cart $cart
     * @return void
     */
    private function manageCartAfterChange(Cart $cart): void
    {
        $this->discounter->apply($cart);
    }
}