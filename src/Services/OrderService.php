<?php

namespace Src\Services;

use Src\DTO\CreateOrderDTO;
use Src\DTO\UpdateOrderDTO;
use Src\Mappers\OrderMapper;
use Src\Models\Order;
use Src\Models\OrderItem;
use Src\Repositories\OrderRepository;
use Src\Repositories\OrderItemRepository;
use Src\Repositories\ItemRepository;
use Src\Core\Database;
use Src\Core\Logger;

class OrderService
{
    private OrderRepository $repository;
    private OrderItemRepository $itemRepository;
    private ItemRepository $productRepository;

    public function __construct()
    {
        $this->repository = new OrderRepository();
        $this->itemRepository = new OrderItemRepository();
        $this->productRepository = new ItemRepository();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?Order
    {
        return $this->repository->findById($id);
    }

    public function createOrder(CreateOrderDTO $dto): void
    {
        if ($dto->customerId <= 0) {
            throw new \InvalidArgumentException('CustomerID is required');
        }
        if ($dto->employeeId <= 0) {
            throw new \InvalidArgumentException('EmployeeID is required');
        }
        
        $dto->shipCity = $dto->shipCity !== null ? trim($dto->shipCity) : null;
        $dto->shipStreet = $dto->shipStreet !== null ? trim($dto->shipStreet) : null;
        $dto->shipZip = $dto->shipZip !== null ? trim($dto->shipZip) : null;

        $order = OrderMapper::fromCreateDTO($dto);
        $this->repository->save($order);
        Logger::info("Order created", [
            'customer_id' => $dto->customerId,
            'employee_id' => $dto->employeeId
        ]);
    }

    public function updateOrder(int $id, UpdateOrderDTO $dto): void
    {
        if ($dto->customerId <= 0) {
            throw new \InvalidArgumentException('CustomerID is required');
        }
        if ($dto->employeeId <= 0) {
            throw new \InvalidArgumentException('EmployeeID is required');
        }
        if (!$this->repository->findById($id)) {
            throw new \RuntimeException('Order not found');
        }

        $dto->shipCity = $dto->shipCity !== null ? trim($dto->shipCity) : null;
        $dto->shipStreet = $dto->shipStreet !== null ? trim($dto->shipStreet) : null;
        $dto->shipZip = $dto->shipZip !== null ? trim($dto->shipZip) : null;

        $order = OrderMapper::fromUpdateDTO($dto);
        $this->repository->update($order);
        Logger::info("Order updated", ['order_id' => $id]);
    }

    public function deleteOrder(int $id): void
    {
        $this->repository->delete($id);
        Logger::info("Order deleted", ['order_id' => $id]);
    }

    

    
    public function getItemsByOrder(int $orderId): array
    {
        if ($orderId <= 0) {
            throw new \InvalidArgumentException('orderId must be positive');
        }
        return $this->itemRepository->findByOrder($orderId);
    }

    public function upsertItem(int $orderId, int $productId, int $quantity, float $soldPrice): void
    {
        if ($orderId <= 0 || $productId <= 0) {
            throw new \InvalidArgumentException('orderId and productId must be positive');
        }
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('quantity must be greater than 0');
        }
        if ($soldPrice < 0) {
            throw new \InvalidArgumentException('soldPrice must be non-negative');
        }

        $product = $this->productRepository->findById($productId);
        if (!$product) {
            throw new \RuntimeException('Product not found');
        }
        if ($soldPrice == 0.0) {
            $soldPrice = (float)$product->price;
        }

        $pdo = Database::getInstance()->getConnection();
        try {
            $pdo->beginTransaction();

            $existing = $this->itemRepository->findOne($orderId, $productId);
            $delta = $quantity - ($existing?->quantity ?? 0);

            if ($delta > 0) {
                $ok = $this->productRepository->decreaseStock($productId, $delta);
                if (!$ok) {
                    throw new \RuntimeException('Insufficient stock');
                }
            } elseif ($delta < 0) {
                $this->productRepository->increaseStock($productId, -$delta);
            }

            $item = new OrderItem(
                id: null,
                orderId: $orderId,
                productId: $productId,
                quantity: $quantity,
                soldPrice: $soldPrice,
            );
            $this->itemRepository->upsert($item);

            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    
    public function getAllItems(): array
    {
        return $this->productRepository->findAll();
    }

    public function removeItem(int $orderId, int $productId): void
    {
        if ($orderId <= 0 || $productId <= 0) {
            throw new \InvalidArgumentException('orderId and productId must be positive');
        }
        $pdo = Database::getInstance()->getConnection();
        try {
            $pdo->beginTransaction();

            $existing = $this->itemRepository->findOne($orderId, $productId);
            if ($existing) {
                
                $this->productRepository->increaseStock($productId, $existing->quantity);
                
                $this->itemRepository->deleteByOrderAndProduct($orderId, $productId);
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    
    public function addItemsBulk(int $orderId, array $items): void
    {
        if ($orderId <= 0) {
            throw new \InvalidArgumentException('orderId must be positive');
        }
        if (empty($items)) {
            return; // nothing to do
        }

        
        $normalized = [];
        foreach ($items as $row) {
            $pid = (int)($row['product_id'] ?? 0);
            $qty = (int)($row['quantity'] ?? 0);
            $price = isset($row['sold_price']) ? (float)$row['sold_price'] : 0.0;
            if ($pid > 0 && $qty > 0) {
                $normalized[$pid] = [
                    'product_id' => $pid,
                    'quantity' => $qty,
                    'sold_price' => $price,
                ];
            }
        }
        if (empty($normalized)) {
            return;
        }

        $pdo = Database::getInstance()->getConnection();
        try {
            $pdo->beginTransaction();

            
            foreach ($normalized as $pid => $row) {
                $qty = $row['quantity'];
                
                $product = $this->productRepository->findById($pid);
                if (!$product) {
                    throw new \RuntimeException('Product not found: #' . $pid);
                }
                $price = $row['sold_price'] == 0.0 ? (float)$product->price : (float)$row['sold_price'];

                $existing = $this->itemRepository->findOne($orderId, $pid);
                $delta = $qty - ($existing?->quantity ?? 0);

                if ($delta > 0) {
                    $ok = $this->productRepository->decreaseStock($pid, $delta);
                    if (!$ok) {
                        throw new \RuntimeException('Insufficient stock for product #' . $pid);
                    }
                } elseif ($delta < 0) {
                    $this->productRepository->increaseStock($pid, -$delta);
                }

                $item = new OrderItem(
                    id: null,
                    orderId: $orderId,
                    productId: $pid,
                    quantity: $qty,
                    soldPrice: $price,
                );
                $this->itemRepository->upsert($item);
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    
    public function getOrderInventory(int $orderId): array
    {
        if ($orderId <= 0) {
            throw new \InvalidArgumentException('orderId must be positive');
        }

        $lines = $this->itemRepository->findByOrder($orderId);

        
        $itemsMap = [];
        foreach ($lines as $li) {
            $pid = $li->productId;
            if (!isset($itemsMap[$pid])) {
                $product = $this->productRepository->findById($pid);
                $itemsMap[$pid] = $product?->name ?? ('#' . $pid);
            }
        }

        $resultItems = [];
        $totalQuantity = 0;
        $totalValue = 0.0;
        foreach ($lines as $li) {
            $lineTotal = $li->quantity * $li->soldPrice;
            $totalQuantity += $li->quantity;
            $totalValue += $lineTotal;
            $resultItems[] = [
                'productId' => $li->productId,
                'name' => $itemsMap[$li->productId] ?? ('#' . $li->productId),
                'quantity' => $li->quantity,
                'soldPrice' => $li->soldPrice,
                'lineTotal' => $lineTotal,
            ];
        }

        return [
            'items' => $resultItems,
            'totals' => [
                'itemCount' => count($resultItems),
                'totalQuantity' => $totalQuantity,
                'totalValue' => $totalValue,
            ],
        ];
    }
}


