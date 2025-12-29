<?php

declare(strict_types=1);

namespace Src\Handlers;

use Src\Core\View;
use Src\DTO\CreateOrderDTO;
use Src\DTO\UpdateOrderDTO;
use Src\Mappers\OrderMapper;
use Src\Services\OrderService;
use Src\Services\CustomerService;
use Src\Services\EmployeeService;

class OrderHandler
{
    private OrderService $service;
    private CustomerService $customerService;
    private EmployeeService $employeeService;
    private const BASE_URL = '/db-lab/public/index.php';

    public function __construct()
    {
        $this->service = new OrderService();
        $this->customerService = new CustomerService();
        $this->employeeService = new EmployeeService();
    }

    public function index(): void
    {
        try {
            $orders = $this->service->getAll();
            $message = null;
            if (isset($_GET['updated'])) {
                $message = 'Order updated.';
            } elseif (isset($_GET['deleted'])) {
                $message = 'Order deleted.';
            }
            View::render('orders/index', [
                'orders' => $orders,
                'title' => 'Orders',
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            View::render('orders/index', [
                'orders' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function show(int $id): void
    {
        if ($id <= 0) {
            http_response_code(404);
            View::render('orders/index', ['orders' => [], 'error' => 'Некоректний ID']);
            return;
        }

        try {
            $order = $this->service->getById($id);
            if (!$order) {
                http_response_code(404);
                View::render('orders/index', [
                    'orders' => [],
                    'error' => 'Order not found',
                ]);
                return;
            }

            
            $inventory = $this->service->getOrderInventory($id);
            
            $allItems = $this->service->getAllItems();

            View::render('orders/show', [
                'order' => $order,
                'inventory' => $inventory,
                'allItems' => $allItems,
                'title' => 'Order details',
            ]);
        } catch (\Throwable $e) {
            View::render('orders/index', [
                'orders' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create(): void
    {
        $customers = $this->customerService->getAll();
        $employees = $this->employeeService->getAll();
        $viewData = [
            'form' => [],
            'customers' => $customers,
            'employees' => $employees
        ];
        if (isset($_GET['success'])) {
            $viewData['message'] = 'Order created!';
        }
        View::render('orders/create', $viewData);
    }

    public function store(): void
    {
        try {
            $dto = CreateOrderDTO::fromArray($_POST);
            $this->service->createOrder($dto);
            header('Location: ' . self::BASE_URL . '?r=order/create&success=1');
            exit;
        } catch (\Throwable $e) {
            $customers = $this->customerService->getAll();
            $employees = $this->employeeService->getAll();
            View::render('orders/create', [
                'error' => $e->getMessage(),
                'form' => $_POST,
                'customers' => $customers,
                'employees' => $employees,
            ]);
        }
    }

    public function edit(int $id): void
    {
        try {
            $order = $this->service->getById($id);
            if (!$order) {
                http_response_code(404);
                View::render('orders/index', ['error' => 'Order not found']);
                return;
            }
            $formData = OrderMapper::toFormArray($order);
            $customers = $this->customerService->getAll();
            $employees = $this->employeeService->getAll();
            View::render('orders/edit', [
                'form' => $formData,
                'customers' => $customers,
                'employees' => $employees
            ]);
        } catch (\Throwable $e) {
            View::render('orders/edit', ['error' => $e->getMessage()]);
        }
    }

    public function update(int $id): void
    {
        try {
            $dto = UpdateOrderDTO::fromArray($_POST);
            $dto->id = $id;
            $this->service->updateOrder($id, $dto);
            header('Location: ' . self::BASE_URL . '?r=order/index&updated=1');
            exit;
        } catch (\Throwable $e) {
            $formData = $_POST;
            $formData['id'] = $id;
            $customers = $this->customerService->getAll();
            $employees = $this->employeeService->getAll();
            View::render('orders/edit', [
                'error' => $e->getMessage(),
                'form' => $formData,
                'customers' => $customers,
                'employees' => $employees,
            ]);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $this->service->deleteOrder($id);
            header('Location: ' . self::BASE_URL . '?r=order/index&deleted=1');
            exit;
        } catch (\Throwable $e) {
            $list = [];
            try { $list = $this->service->getAll(); } catch (\Throwable $_) {}
            View::render('orders/index', [
                'orders' => $list,
                'error' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    
    public function addItem(int $id): void
    {
        try {
            $productId = (int)($_POST['product_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 0);
            $soldPrice = (float)($_POST['sold_price'] ?? 0);

            
            $errors = [];
            if ($id <= 0) {
                $errors[] = 'ID замовлення має бути додатним числом';
            }
            if ($productId <= 0) {
                $errors[] = 'ID товару має бути додатним числом';
            }
            if ($quantity <= 0) {
                $errors[] = 'Кількість має бути більше 0';
            }
            if ($soldPrice <= 0) {
                $errors[] = 'Ціна продажу має бути більше 0';
            }

            if (!empty($errors)) {
                throw new \InvalidArgumentException(implode('; ', $errors));
            }

            $this->service->upsertItem($id, $productId, $quantity, $soldPrice);
            header('Location: ' . self::BASE_URL . '?r=order/show&id=' . $id . '&item_added=1');
            exit;
        } catch (\Throwable $e) {
	        error_log('[addItem] ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
	        error_log($e->getTraceAsString());
            
            try {
                $order = $this->service->getById($id);
                $inventory = $this->service->getOrderInventory($id);
                $allItems = $this->service->getAllItems();
            } catch (\Throwable $_) {
	            error_log('[addItem] ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
	            error_log($e->getTraceAsString());
                $order = null; $inventory = []; $allItems = [];
            }
            View::render('orders/show', [
                'order' => $order,
                'inventory' => $inventory,
                'allItems' => $allItems,
                'title' => 'Order details',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function addItems(int $id): void
    {
        try {
            $raw = $_POST['items'] ?? [];
            $items = [];
            if (is_array($raw)) {
                foreach ($raw as $row) {
                    $pid = (int)($row['product_id'] ?? 0);
                    $qty = (int)($row['quantity'] ?? 0);
                    $price = isset($row['sold_price']) ? (float)$row['sold_price'] : 0.0;
                    if ($pid > 0 && $qty > 0) {
                        $items[] = [
                            'product_id' => $pid,
                            'quantity' => $qty,
                            'sold_price' => $price,
                        ];
                    }
                }
            }

            if (empty($items)) {
                header('Location: ' . self::BASE_URL . '?r=order/show&id=' . $id);
                exit;
            }

            $this->service->addItemsBulk($id, $items);
            header('Location: ' . self::BASE_URL . '?r=order/show&id=' . $id . '&item_added=1');
            exit;
        } catch (\Throwable $e) {
            try {
                $order = $this->service->getById($id);
                $inventory = $this->service->getOrderInventory($id);
                $allItems = $this->service->getAllItems();
            } catch (\Throwable $_) {
                $order = null; $inventory = []; $allItems = [];
            }
            View::render('orders/show', [
                'order' => $order,
                'inventory' => $inventory,
                'allItems' => $allItems,
                'title' => 'Order details',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function removeItem(int $id): void
    {
        try {
            $productId = (int)($_POST['product_id'] ?? $_GET['product_id'] ?? 0);
            $this->service->removeItem($id, $productId);
            header('Location: ' . self::BASE_URL . '?r=order/show&id=' . $id . '&item_removed=1');
            exit;
        } catch (\Throwable $e) {
            try {
                $order = $this->service->getById($id);
                $inventory = $this->service->getOrderInventory($id);
                $allItems = $this->service->getAllItems();
            } catch (\Throwable $_) {
                $order = null; $inventory = []; $allItems = [];
            }
            View::render('orders/show', [
                'order' => $order,
                'inventory' => $inventory,
                'allItems' => $allItems,
                'title' => 'Order details',
                'error' => $e->getMessage(),
            ]);
        }
    }
}


