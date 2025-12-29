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
        $id = (int)($data['id'] ?? 0);
        $name = trim((string)($data['name'] ?? ''));
        $price = (float)($data['price'] ?? 0);
        $quantity = (int)($data['quantity'] ?? 0);
        $guarantee = (int)($data['guarantee'] ?? 0);

        
        $errors = [];
        if ($id <= 0) {
            $errors[] = 'ID має бути додатним числом';
        }

        if (empty($name)) {
            $errors[] = 'Назва товару обов\'язкова';
        } elseif (strlen($name) > 255) {
            $errors[] = 'Назва товару має містити не більше 255 символів';
        }

        if ($price <= 0) {
            $errors[] = 'Ціна має бути більше 0';
        } elseif ($price > 999999.99) {
            $errors[] = 'Ціна занадто висока';
        }

        if ($quantity < 0) {
            $errors[] = 'Кількість не може бути від\'ємною';
        }

        if ($guarantee < 0) {
            $errors[] = 'Гарантія не може бути від\'ємною';
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException(implode('; ', $errors));
        }

        return new self(
            $id,
            $name,
            $price,
            $quantity,
            $guarantee
        );
    }
}


