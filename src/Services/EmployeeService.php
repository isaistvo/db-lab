<?php

namespace Src\Services;

use Src\Repositories\EmployeeRepository;
use Src\DTO\CreateEmployeeDTO;
use Src\DTO\UpdateEmployeeDTO;
use Src\Mappers\EmployeeMapper;
use Src\Models\Employee;
use Src\Core\Logger;

class EmployeeService
{
	private EmployeeRepository $repository;

	public function __construct()
	{
		$this->repository = new EmployeeRepository();
	}

	public function getAll(): array
	{
		return $this->repository->findAll();
	}

	public function getById(int $id): ?Employee
	{
		return $this->repository->findById($id);
	}

	public function createEmployee(CreateEmployeeDTO $dto): void
	{
		if (empty($dto->firstName) || empty($dto->lastName)) {
			throw new \InvalidArgumentException("Ім'я та прізвище обов'язкові.");
		}
		$employee = EmployeeMapper::fromCreateDTO($dto);
		$this->repository->save($employee);
		Logger::info("Employee created", [
			'first_name' => $dto->firstName,
			'last_name' => $dto->lastName
		]);
	}

	public function updateEmployee(int $id, UpdateEmployeeDTO $dto): void
	{
		if (empty($dto->firstName) || empty($dto->lastName)) {
			throw new \InvalidArgumentException("Ім'я та прізвище обов'язкові.");
		}
		$existing = $this->repository->findById($id);
		if (!$existing) {
			throw new \RuntimeException('Співробітника не знайдено');
		}
		$updated = EmployeeMapper::fromUpdateDTO($dto);
		$this->repository->update($updated);
		Logger::info("Employee updated", ['employee_id' => $id]);
	}

	public function deleteEmployee(int $id): void
	{
		$existing = $this->repository->findById($id);
		if (!$existing) {
			throw new \RuntimeException('Співробітника не знайдено');
		}
		$this->repository->delete($id);
		Logger::info("Employee deleted", ['employee_id' => $id]);
	}
}