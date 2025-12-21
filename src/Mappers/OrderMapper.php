<?php

namespace Src\Mappers;

use DateTimeImmutable;
use Src\DTO\CreateOrderDTO;
use Src\DTO\UpdateOrderDTO;
use Src\Models\Order;

class OrderMapper
{
    public static function fromCreateDTO(CreateOrderDTO $dto): Order
    {
        return new Order(
            id: null,
            customerId: $dto->customerId,
            employeeId: $dto->employeeId,
            shipCity: $dto->shipCity,
            shipStreet: $dto->shipStreet,
            shipZip: $dto->shipZip,
            shipDate: $dto->shipDate,
        );
    }

    public static function fromUpdateDTO(UpdateOrderDTO $dto): Order
    {
        return new Order(
            id: $dto->id,
            customerId: $dto->customerId,
            employeeId: $dto->employeeId,
            shipCity: $dto->shipCity,
            shipStreet: $dto->shipStreet,
            shipZip: $dto->shipZip,
            shipDate: $dto->shipDate,
        );
    }

    public static function fromDBRow(array $row): Order
    {
        $dateStr = $row['ShipDate'] ?? null;
        $date = null;
        if (!empty($dateStr)) {
            try { $date = new DateTimeImmutable($dateStr); } catch (\Throwable $e) { $date = null; }
        }
        return new Order(
            id: (int)($row['OrderID'] ?? $row['id'] ?? 0),
            customerId: (int)$row['CustomerID'],
            employeeId: (int)$row['EmployeeID'],
            shipCity: isset($row['ShipCity']) ? (string)$row['ShipCity'] : null,
            shipStreet: isset($row['ShipStreet']) ? (string)$row['ShipStreet'] : null,
            shipZip: isset($row['ShipZip']) ? (string)$row['ShipZip'] : null,
            shipDate: $date,
        );
    }

    public static function toFormArray(Order $order): array
    {
        return [
            'id' => $order->id,
            'customer_id' => $order->customerId,
            'employee_id' => $order->employeeId,
            'ship_city' => $order->shipCity,
            'ship_street' => $order->shipStreet,
            'ship_zip' => $order->shipZip,
            'ship_date' => $order->shipDate?->format('Y-m-d'),
        ];
    }
}
