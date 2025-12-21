<?php

namespace Src\Mappers;

use Src\Models\OrderItem;

class OrderItemMapper
{
    public static function fromDBRow(array $row): OrderItem
    {
        return new OrderItem(
            id: isset($row['ID']) ? (int)$row['ID'] : (isset($row['id']) ? (int)$row['id'] : null),
            orderId: (int)($row['OrderID'] ?? $row['order_id'] ?? 0),
            productId: (int)($row['ProductID'] ?? $row['product_id'] ?? 0),
            quantity: (int)($row['Quantity'] ?? $row['quantity'] ?? 0),
            soldPrice: (float)($row['SoldPrice'] ?? $row['sold_price'] ?? 0.0),
        );
    }

    public static function toDBParams(OrderItem $item): array
    {
        return [
            ':orderId'   => $item->orderId,
            ':productId' => $item->productId,
            ':quantity'  => $item->quantity,
            ':soldPrice' => $item->soldPrice,
        ];
    }
}
