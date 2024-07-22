<?php

declare(strict_types=1);

namespace App\Domain\Discount;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final readonly class Discount
{
    private UuidInterface $uuid;

    public function __construct(private string $name, private int $amount)
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public static function compareDiscountsAndReturnBetter(self $firstDiscount, self $secondDiscount): self
    {
        return $firstDiscount->getAmount() > $secondDiscount->getAmount()
            ? $firstDiscount
            : $secondDiscount;
    }
}