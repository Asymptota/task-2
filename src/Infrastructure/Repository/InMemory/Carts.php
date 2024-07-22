<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Cart\Cart;
use App\Application\Exception\CartNotFoundException;
use App\Infrastructure\Repository\CartsRepository;
use Ramsey\Uuid\UuidInterface;

class Carts implements CartsRepository
{
    /**
     * @var Cart[]
     */
    private array $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    /**
     * @param Cart $cart
     * @return void
     */
    public function add(Cart $cart): void
    {
        $this->collection[$cart->getUuid()->toString()] = $cart;
    }

    /**
     * @param Cart $cart
     * @return void
     */
    public function remove(Cart $cart): void
    {
        unset($this->collection[$cart->getUuid()->toString()]);
    }

    /**
     * @param UuidInterface $uuid
     * @return Cart
     * @throws CartNotFoundException
     */
    public function getByUuid(UuidInterface $uuid): Cart
    {
        return $this->collection[$uuid->toString()] ?? throw CartNotFoundException::create($uuid->toString());
    }

    /**
     * @param UuidInterface $uuid
     * @return Cart|null
     */
    public function findByUuid(UuidInterface $uuid): ?Cart
    {
        return $this->collection[$uuid->toString()] ?? null;
    }

    /**
     * @param Cart $cart
     * @return void
     */
    public function save(Cart $cart): void
    {
        $this->collection[$cart->getUuid()->toString()] = $cart;
    }
}