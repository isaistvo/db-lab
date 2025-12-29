<?php

namespace Src\Models;

readonly class Item
{
    public function __construct(
        public ?int $id,
        public string $name,
        public float $price,
        public int $quantity,
        public int $guarantee
    ) {}
}


