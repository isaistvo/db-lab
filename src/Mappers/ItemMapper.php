<?php

namespace Src\Mappers;

use Src\DTO\CreateItemDTO;
use Src\DTO\UpdateItemDTO;
use Src\Models\Item;

class ItemMapper
{
    public static function fromCreateDTO(CreateItemDTO $dto): Item
    {
        return new Item(null, $dto->name, $dto->price, $dto->quantity, $dto->guarantee);
    }

    public static function fromUpdateDTO(UpdateItemDTO $dto): Item
    {
        return new Item($dto->id, $dto->name, $dto->price, $dto->quantity, $dto->guarantee);
    }

    public static function fromDBRow(array $row): Item
    {
        return new Item(
            id: (int)($row['ProductID'] ?? $row['id']),
            name: (string)($row['Name'] ?? ''),
            price: (float)($row['Price'] ?? 0),
            quantity: (int)$row['Quantity'],
            guarantee: (int)$row['Guarantee']
        );
    }

    public static function toFormArray(Item $item): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'guarantee' => $item->guarantee
        ];
    }
}


