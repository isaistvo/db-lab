<?php

namespace Src\DTO;

class UpdateItemDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public int $quantity,
        public int $guarantee
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            (int)($data['id'] ?? 0),
            trim((string)($data['name'] ?? '')),
            (float)($data['price'] ?? 0),
            (int)($data['quantity'] ?? 0),
            (int)($data['guarantee'] ?? 0)
        );
    }
}