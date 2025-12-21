<?php

namespace Src\Repositories;

use PDO;
use Src\Core\Database;
use Src\Core\Logger;
use Src\Mappers\OrderItemMapper;
use Src\Models\OrderItem;

class OrderItemRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findOne(int $orderId, int $productId): ?OrderItem
    {
        $stmt = $this->db->prepare("SELECT * FROM orderitems WHERE OrderID = :orderId AND ProductID = :productId LIMIT 1");
        $stmt->execute([':orderId' => $orderId, ':productId' => $productId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			Logger::info("OrderItem not found", ['order_id' => $orderId, 'product_id' => $productId]);
			return null;
		}
		Logger::debug("OrderItem found", ['order_id' => $orderId, 'product_id' => $productId]);
        return $row ? OrderItemMapper::fromDBRow($row) : null;
    }

    public function add(OrderItem $item): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO orderitems (OrderID, ProductID, Quantity, SoldPrice)
             VALUES (:orderId, :productId, :quantity, :soldPrice)"
        );
        $stmt->execute([
            'orderId'   => $item->orderId,
            'productId' => $item->productId,
            'quantity'  => $item->quantity,
            'soldPrice' => $item->soldPrice,
        ]);
		Logger::info("OrderItem added", ['order_id' => $item->orderId, 'product_id' => $item->productId]);
    }

    public function upsert(OrderItem $item): void
    {
        // Uses unique (OrderID, ProductID)
        $stmt = $this->db->prepare(
            "INSERT INTO orderitems (OrderID, ProductID, Quantity, SoldPrice)
             VALUES (:orderId, :productId, :quantity, :soldPrice)
             ON DUPLICATE KEY UPDATE Quantity = VALUES(Quantity), SoldPrice = VALUES(SoldPrice)"
        );
        $stmt->execute([
            'orderId'   => $item->orderId,
            'productId' => $item->productId,
            'quantity'  => $item->quantity,
            'soldPrice' => $item->soldPrice,
        ]);
		Logger::info("OrderItem upserted", ['order_id' => $item->orderId, 'product_id' => $item->productId]);
    }

    public function update(OrderItem $item): void
    {
        $stmt = $this->db->prepare(
            "UPDATE orderitems
             SET Quantity = :quantity, SoldPrice = :soldPrice
             WHERE OrderID = :orderId AND ProductID = :productId"
        );
        $stmt->execute([
            'orderId'   => $item->orderId,
            'productId' => $item->productId,
            'quantity'  => $item->quantity,
            'soldPrice' => $item->soldPrice,
        ]);
		Logger::info("OrderItem updated", ['order_id' => $item->orderId, 'product_id' => $item->productId]);
    }

    public function deleteByOrderAndProduct(int $orderId, int $productId): void
    {
        $stmt = $this->db->prepare("DELETE FROM orderitems WHERE OrderID = :orderId AND ProductID = :productId");
        $stmt->execute([':orderId' => $orderId, ':productId' => $productId]);
    }

    public function deleteByOrder(int $orderId): void
    {
        $stmt = $this->db->prepare("DELETE FROM orderitems WHERE OrderID = :orderId");
        $stmt->execute([':orderId' => $orderId]);
    }

    /**
     * @return OrderItem[]
     */
    public function findByOrder(int $orderId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM orderitems WHERE OrderID = :orderId ORDER BY ID ASC");
        $stmt->execute([':orderId' => $orderId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => OrderItemMapper::fromDBRow($row), $rows);
    }
}
