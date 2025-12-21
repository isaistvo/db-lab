<?php

namespace Src\Repositories;

use PDO;
use Src\Core\Database;
use Src\Mappers\OrderMapper;
use Src\Models\Order;

class OrderRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(Order $order): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO orders (CustomerID, EmployeeID, ShipCity, ShipStreet, ShipZip, ShipDate)
             VALUES (:customerId, :employeeId, :shipCity, :shipStreet, :shipZip, :shipDate)"
        );
        $stmt->execute([
            ':customerId' => $order->customerId,
            ':employeeId' => $order->employeeId,
            ':shipCity'   => $order->shipCity,
            ':shipStreet' => $order->shipStreet,
            ':shipZip'    => $order->shipZip,
            ':shipDate'   => $order->shipDate?->format('Y-m-d'),
        ]);
    }

    public function update(Order $order): void
    {
        $stmt = $this->db->prepare(
            "UPDATE orders
             SET CustomerID = :customerId,
                 EmployeeID = :employeeId,
                 ShipCity   = :shipCity,
                 ShipStreet = :shipStreet,
                 ShipZip    = :shipZip,
                 ShipDate   = :shipDate
             WHERE OrderID = :id"
        );
        $stmt->execute([
            ':id'         => $order->id,
            ':customerId' => $order->customerId,
            ':employeeId' => $order->employeeId,
            ':shipCity'   => $order->shipCity,
            ':shipStreet' => $order->shipStreet,
            ':shipZip'    => $order->shipZip,
            ':shipDate'   => $order->shipDate?->format('Y-m-d'),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE OrderID = :id");
        $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM orders ORDER BY OrderID DESC");
        return array_map(fn($row) => OrderMapper::fromDBRow($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findById(int $id): ?Order
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE OrderID = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? OrderMapper::fromDBRow($row) : null;
    }
}