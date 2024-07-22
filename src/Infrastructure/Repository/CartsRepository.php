<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use Ramsey\Uuid\UuidInterface;

interface CartsRepository
{
    /**
     * @param Cart $cart
     * @return void
     */
    public function add(Cart $cart): void;

    /**
     * @param Cart $cart
     * @return void
     */
    public function remove(Cart $cart): void;

    /**
     * @param UuidInterface $uuid
     * @return Cart
     */
    public function getByUuid(UuidInterface $uuid): Cart;

    /**
     * @param UuidInterface $uuid
     * @return ?Cart
     */
    public function findByUuid(UuidInterface $uuid): ?Cart;

    /**
     * @param Cart $cart
     * @return void
     */
    public function save(Cart $cart): void;
}