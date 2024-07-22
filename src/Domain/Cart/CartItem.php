<?php

declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Product\Product;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CartItem
{
    /**
     * @var UuidInterface
     */
    private UuidInterface $uuid;

    private Product $product;

    private int $quantity;

    /**
     * @param UuidInterface|null $uuid
     */
    public function __construct(?UuidInterface $uuid = null)
    {
        $this->uuid = $uuid ?? Uuid::uuid4();
    }


    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getPrice(): int
    {
        return $this->product->getPrice();
    }

    public function getProductName(): string
    {
        return $this->product->getName();
    }
}