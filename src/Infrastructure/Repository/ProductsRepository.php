<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Product\Product;
use Ramsey\Uuid\UuidInterface;

interface ProductsRepository
{
    /**
     * @param Product $product
     * @return void
     */
    public function add(Product $product): void;

    /**
     * @param Product $product
     * @return void
     */
    public function remove(Product $product): void;

    /**
     * @param UuidInterface $uuid
     * @return Product
     */
    public function getByUuid(UuidInterface $uuid): Product;
}