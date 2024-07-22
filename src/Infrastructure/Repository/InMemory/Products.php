<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Application\Exception\ProductNotFoundException;
use App\Infrastructure\Repository\ProductsRepository;
use App\Domain\Product\Product;
use Ramsey\Uuid\UuidInterface;

class Products implements ProductsRepository
{
    /**
     * @var Product[]
     */
    private array $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    /**
     * @param Product $product
     * @return void
     */
    public function add(Product $product): void
    {
        $this->collection[$product->getUuid()->toString()] = $product;
    }

    public function remove(Product $product): void
    {
        unset($this->collection[$product->getUuid()->toString()]);
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getByUuid(UuidInterface $uuid): Product
    {
        return $this->collection[$uuid->toString()] ?? throw ProductNotFoundException::create($uuid->toString());
    }
}