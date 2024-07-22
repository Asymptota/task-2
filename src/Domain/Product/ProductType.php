<?php

declare(strict_types=1);

namespace App\Domain\Product;

enum ProductType: string
{
    case CANDY = 'candy';
    case DRINK = 'drink';
    case BREAD = 'bread';
}
