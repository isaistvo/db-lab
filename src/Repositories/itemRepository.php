<?php

namespace Src\Repositories;

use Src\Core\Database;
use Src\Core\Logger;
use Src\Models\Item;
use Src\Mappers\ItemMapper;
use PDO;

class ItemRepository
{
    private PDO $db;

	public function __construct()
	{
		$this->db = Database::getInstance()->getConnection();
 }

 /**
  * Atomically decreases stock if available. Returns true on success, false if insufficient stock.
  */
 public function decreaseStock(int $productId, int $by): bool
 {
     if ($by <= 0) { return true; }
     // Use distinct named parameters to avoid HY093 with native prepares
     $stmt = $this->db->prepare(
         "UPDATE Items
          SET Quantity = Quantity - :byDec
          WHERE ProductID = :id AND Quantity >= :byCmp"
     );
     $stmt->execute([':id' => $productId, ':byDec' => $by, ':byCmp' => $by]);
     return $stmt->rowCount() > 0;
 }

 /**
  * Increases stock by given amount (can be used to revert or on item removal from order).
  */
 public function increaseStock(int $productId, int $by): void
 {
     if ($by <= 0) { return; }
     $stmt = $this->db->prepare("UPDATE Items SET Quantity = Quantity + :by WHERE ProductID = :id");
     $stmt->execute([':id' => $productId, ':by' => $by]);
 }

 public function save(Item $item): void
 {
     $stmt = $this->db->prepare("INSERT INTO Items (Name, Price, Quantity, Guarantee) VALUES (:name, :price, :qty, :guar)");
     $stmt->execute([
         ':name' => $item->name,
         ':price' => $item->price,
         ':qty' => $item->quantity,
         ':guar' => $item->guarantee
     ]);
	 Logger::info("Item saved", ['name' => $item->name]);
 }

 public function update(Item $item): void
 {
     $stmt = $this->db->prepare("UPDATE Items SET Name = :name, Price = :price, Quantity = :qty, Guarantee = :guar WHERE ProductID = :id");
     $stmt->execute([
         ':id' => $item->id,
         ':name' => $item->name,
         ':price' => $item->price,
         ':qty' => $item->quantity,
         ':guar' => $item->guarantee
     ]);
	 Logger::info("Item updated", ['item_id' => $item->id]);
 }

	public function delete(int $id): void
	{
		$stmt = $this->db->prepare("DELETE FROM Items WHERE ProductID = :id");
		$stmt->execute([':id' => $id]);
		Logger::info("Item deleted", ['item_id' => $id]);
	}

	public function findAll(): array
	{
		$stmt = $this->db->query("SELECT * FROM Items ORDER BY ProductID DESC");
		return array_map(fn($row) => ItemMapper::fromDBRow($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
	}

	public function findById(int $id): ?Item
	{
		$stmt = $this->db->prepare("SELECT * FROM Items WHERE ProductID = :id LIMIT 1");
		$stmt->execute([':id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			Logger::info("Item not found", ['item_id' => $id]);
			return null;
		}
		Logger::debug("Item found", ['item_id' => $id]);
		return $row ? ItemMapper::fromDBRow($row) : null;
	}
}