<?php

declare(strict_types=1);

namespace Src\Handlers;

use Src\Core\View;
use Src\Core\Logger;
use Src\DTO\CreateItemDTO;
use Src\DTO\UpdateItemDTO;
use Src\Mappers\ItemMapper;
use Src\Services\ItemService;

class ItemHandler
{
    private ItemService $service;
    private const BASE_URL = '/db-lab/public/index.php';

    public function __construct()
    {
        $this->service = new ItemService();
    }

    public function index(): void
    {
        try {
            $items = $this->service->getAll();
            $message = null;
            if (isset($_GET['updated'])) {
                $message = 'Товар оновлено.';
            } elseif (isset($_GET['deleted'])) {
                $message = 'Товар видалено.';
            }

            View::render('items/index', [
                'items' => $items,
                'title' => 'Список товарів',
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            Logger::error("Failed to load item list", ['error' => $e->getMessage()]);
            View::render('items/index', [
                'items' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function show(int $id): void
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                http_response_code(404);
                View::render('items/index', [
                    'items' => [],
                    'error' => 'Товар не знайдено',
                ]);
                return;
            }

            View::render('items/show', [
                'item' => $item,
                'title' => 'Перегляд товару',
            ]);
        } catch (\Throwable $e) {
            View::render('items/index', [
                'items' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function create(): void
    {
        $viewData = ['form' => []];
        if (isset($_GET['success'])) {
            $viewData['message'] = 'Товар створено!';
        }
        View::render('items/create', $viewData);
    }

    public function store(): void
    {
        try {
            $dto = CreateItemDTO::fromArray($_POST);
            $this->service->createItem($dto);
            Logger::info("Item created via handler", [
                'name' => $dto->name,
                'price' => $dto->price
            ]);
            header('Location: ' . self::BASE_URL . '?r=item/create&success=1');
            exit;
        } catch (\Throwable $e) {
            Logger::error("Failed to create item via handler", [
                'error' => $e->getMessage(),
                'input' => $_POST
            ]);
            View::render('items/create', [
                'error' => $e->getMessage(),
                'form' => $_POST,
            ]);
        }
    }

    public function edit(int $id): void
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) {
                http_response_code(404);
                View::render('items/index', ['error' => 'Товар не знайдено']);
                return;
            }
            $formData = ItemMapper::toFormArray($item);
            View::render('items/edit', ['form' => $formData]);
        } catch (\Throwable $e) {
            View::render('items/edit', ['error' => $e->getMessage()]);
        }
    }

    public function update(int $id): void
    {
        try {
            $dto = UpdateItemDTO::fromArray($_POST);
            $dto->id = $id;
            $this->service->updateItem($id, $dto);
            Logger::info("Item updated via handler", ['id' => $id]);
            header('Location: ' . self::BASE_URL . '?r=item/index&updated=1');
            exit;
        } catch (\Throwable $e) {
            Logger::error("Failed to update item via handler", [
                'error' => $e->getMessage(),
                'id' => $id,
                'input' => $_POST
            ]);
            $formData = $_POST;
            $formData['id'] = $id;
            View::render('items/edit', [
                'error' => $e->getMessage(),
                'form' => $formData,
            ]);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $this->service->deleteItem($id);
            header('Location: ' . self::BASE_URL . '?r=item/index&deleted=1');
            exit;
        } catch (\PDOException $e) {
            $errorMessage = $e->getCode() === '23000'
                ? 'Неможливо видалити товар: можливо, він використовується в замовленнях.'
                : 'Помилка бази даних: ' . $e->getMessage();

            $list = [];
            try { $list = $this->service->getAll(); } catch (\Throwable $_) {}

            View::render('items/index', [
                'items' => $list,
                'error' => $errorMessage,
            ]);
        } catch (\Throwable $e) {
            $list = [];
            try { $list = $this->service->getAll(); } catch (\Throwable $_) {}
            View::render('items/index', [
                'items' => $list,
                'error' => 'Сталася помилка: ' . $e->getMessage(),
            ]);
        }
    }
}
