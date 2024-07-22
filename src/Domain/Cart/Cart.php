<?php

declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Discount\Discount;
use DomainException;
use Ramsey\Uuid\UuidInterface;

class Cart
{
    /**
     * @var CartItem[]
     */
    private array $cartItems;

    /**
     * @var Discount[]
     */
    private array $discounts;

    public CartTotals $cartTotals;

    /**
     * @param UuidInterface $uuid
     */
    public function __construct(private readonly UuidInterface $uuid)
    {
        $this->cartItems = [];
        $this->discounts = [];
        $this->cartTotals = new CartTotals();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return Discount[]
     */
    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function addDiscount(Discount $discount): void
    {
        if (empty($this->cartItems)) {
            throw new DomainException('Cannot add discount to an empty cart.');
        }

        $this->discounts[$discount->getUuid()->toString()] = $discount;

        $this->cartTotals->discountValue += $discount->getAmount();
    }

    public function clearDiscounts(): void
    {
        $this->discounts = [];
        $this->cartTotals->discountValue = 0;
    }

    public function getCartItemByUuid(UuidInterface $uuid): CartItem
    {
        return $this->cartItems[$uuid->toString()];
    }

    public function addCartItem(CartItem $cartItem): void
    {
        $uuid = $cartItem->getUuid()->toString();
        if (isset($this->cartItems[$uuid])) {
            throw new DomainException('Product already present in cart.');
        }

        $this->cartItems[$uuid] = $cartItem;

        $this->recalculateTotals();
    }

    public function removeCartItem(CartItem $cartItem): void
    {
        unset($this->cartItems[$cartItem->getUuid()->toString()]);

        $this->recalculateTotals();
    }

    public function updateCartItemQuantity(CartItem $cartItemUpdated): void
    {
        $this->cartItems[$cartItemUpdated->getUuid()->toString()] = $cartItemUpdated;

        $this->recalculateTotals();
    }

    public function clearCart(): void
    {
        $this->cartItems = [];
        $this->discounts = [];
        $this->zeroTotals();
    }

    public function getTotalPrice(): int
    {
        return $this->cartTotals->totalPrice;
    }

    public function getTotalDiscount(): int
    {
        return $this->cartTotals->discountValue;
    }

    public function getFinalTotalPrice(): int
    {
        return $this->cartTotals->getTotalPriceAfterDiscount();
    }

    /**
     * @return CartItem[]
     */
    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    private function recalculateTotals(): void
    {
        $sum = 0;
        foreach ($this->cartItems as $item) {
            $sum += $item->getQuantity() * $item->getPrice();
        }

        $this->cartTotals->totalPrice = $sum;
        $this->cartTotals->discountValue = 0;
    }

    private function zeroTotals(): void
    {
        $this->cartTotals->totalPrice = 0;
        $this->cartTotals->discountValue = 0;
    }
}