<?php

namespace Src\DTO;

class CreateItemDTO
{
    public function __construct(
        public string $name,
        public float $price,
        public int $quantity,
        public int $guarantee
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            trim((string)($data['name'] ?? '')),
            (float)($data['price'] ?? 0),
            (int)($data['quantity'] ?? 0),
            (int)($data['guarantee'] ?? 0)
        );
    }
}