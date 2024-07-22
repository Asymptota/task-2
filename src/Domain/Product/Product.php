<?php

declare(strict_types=1);

namespace App\Domain\Product;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class Product
{
    private UuidInterface $uuid;

    /**
     * @param ProductType $type
     * @param int $price
     * @param string $name
     */
    public function __construct(
        private ProductType $type,
        private int $price,
        private string $name
    )
    {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return ProductType
     */
    public function getType(): ProductType
    {
        return $this->type;
    }

    //Integer was use fo sake of simplicity. Money package should be used for calculations: https://github.com/moneyphp/money
    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}