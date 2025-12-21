<?php

namespace Src\Services;

use Src\Repositories\ItemRepository;
use Src\DTO\CreateItemDTO;
use Src\DTO\UpdateItemDTO;
use Src\Mappers\ItemMapper;
use Src\Models\Item;

class ItemService
{
	private ItemRepository $repository;

	public function __construct()
	{
		$this->repository = new ItemRepository();
	}

	public function getAll(): array
	{
		return $this->repository->findAll();
	}

	public function getById(int $id): ?Item
	{
		return $this->repository->findById($id);
	}

 public function createItem(CreateItemDTO $dto): void
 {
     if ($dto->quantity < 0) {
         throw new \InvalidArgumentException("Кількість не може бути від'ємною");
     }
     if ($dto->price < 0) {
         throw new \InvalidArgumentException("Ціна не може бути від'ємною");
     }
     if ($dto->name === '') {
         throw new \InvalidArgumentException("Назва є обов'язковою");
     }
     $item = ItemMapper::fromCreateDTO($dto);
     $this->repository->save($item);
 }

	public function updateItem(int $id, UpdateItemDTO $dto): void
	{
        if ($dto->quantity < 0) {
            throw new \InvalidArgumentException("Кількість не може бути від'ємною");
        }
        if ($dto->price < 0) {
            throw new \InvalidArgumentException("Ціна не може бути від'ємною");
        }
        if ($dto->name === '') {
            throw new \InvalidArgumentException("Назва є обов'язковою");
        }
        if (!$this->repository->findById($id)) {
            throw new \RuntimeException("Товар не знайдено");
        }
        $item = ItemMapper::fromUpdateDTO($dto);
        $this->repository->update($item);
    }

	public function deleteItem(int $id): void
	{
		// Тут можна додати перевірку: не видаляти, якщо товар є в замовленнях
		$this->repository->delete($id);
	}
}