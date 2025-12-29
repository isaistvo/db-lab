<?php

declare(strict_types=1);

namespace Src\Handlers;

use Src\Services\EmployeeService;
use Src\Core\View;
use Src\Core\Logger;
use Src\DTO\CreateEmployeeDTO;
use Src\DTO\UpdateEmployeeDTO;
use Src\Models\Employee;
use Src\Mappers\EmployeeMapper;

class EmployeeHandler
{
	private EmployeeService $service;
	private const BASE_URL = '/db-lab/public/index.php';

	public function __construct()
	{
		$this->service = new EmployeeService();
	}

 public function index(): void
 {
		try {
			$employees = $this->service->getAll();
			$message = null;
			if (isset($_GET['updated'])) $message = 'Співробітника оновлено.';
			elseif (isset($_GET['deleted'])) $message = 'Співробітника видалено.';

			View::render('employees/index', [
				'employees' => $employees,
				'title' => 'Список співробітників',
				'message' => $message,
			]);
		} catch (\Throwable $e) {
			Logger::error("Failed to load employee list", ['error' => $e->getMessage()]);
			View::render('employees/index', ['employees' => [], 'error' => $e->getMessage()]);
		}
	}

  public function show(int $id): void
  {
      if ($id <= 0) {
          http_response_code(404);
          View::render('employees/index', ['employees' => [], 'error' => 'Некоректний ID']);
          return;
      }

      try {
          $employee = $this->service->getById($id);
          if (!$employee) {
              http_response_code(404);
              View::render('employees/index', [
                  'employees' => [],
                  'error' => 'Співробітника не знайдено',
              ]);
              return;
          }

          View::render('employees/show', [
              'employee' => $employee,
              'title' => 'Перегляд співробітника',
          ]);
      } catch (\Throwable $e) {
          View::render('employees/index', [
              'employees' => [],
              'error' => $e->getMessage(),
          ]);
      }
  }

	public function create(): void
	{
		$viewData = ['form' => []];
		if (isset($_GET['success'])) $viewData['message'] = 'Співробітника створено!';
		View::render('employees/create', $viewData);
	}

	public function store(): void
	{
		try {
			$dto = CreateEmployeeDTO::fromArray($_POST);
			$this->service->createEmployee($dto);
			Logger::info("Employee created via handler", [
				'first_name' => $dto->firstName,
				'last_name' => $dto->lastName
			]);
			header('Location: ' . self::BASE_URL . '?r=employee/create&success=1');
			exit;
		} catch (\Throwable $e) {
			Logger::error("Failed to create employee via handler", [
				'error' => $e->getMessage(),
				'input' => $_POST
			]);
			View::render('employees/create', ['error' => $e->getMessage(), 'form' => $_POST]);
		}
	}

	public function edit(int $id): void
	{
		try {
			$employee = $this->service->getById($id);
			if (!$employee) {
				http_response_code(404);
				View::render('employees/index', ['error' => 'Співробітника не знайдено']);
				return;
			}
			$formData = EmployeeMapper::toFormArray($employee);
			View::render('employees/edit', ['form' => $formData]);
		} catch (\Throwable $e) {
			View::render('employees/edit', ['error' => $e->getMessage()]);
		}
	}

	public function update(int $id): void
	{
		try {
			$dto = UpdateEmployeeDTO::fromArray($_POST);
			$dto->id = $id;
			$this->service->updateEmployee($id, $dto);
			Logger::info("Employee updated via handler", ['id' => $id]);
			header('Location: ' . self::BASE_URL . '?r=employee/index&updated=1');
			exit;
		} catch (\Throwable $e) {
			Logger::error("Failed to update employee via handler", [
				'error' => $e->getMessage(),
				'id' => $id,
				'input' => $_POST
			]);
			$formData = $_POST;
			$formData['id'] = $id;
			View::render('employees/edit', ['error' => $e->getMessage(), 'form' => $formData]);
		}
	}

	public function destroy(int $id): void
	{
		try {
			$this->service->deleteEmployee($id);
			Logger::info("Employee deleted via handler", ['id' => $id]);
			header('Location: ' . self::BASE_URL . '?r=employee/index&deleted=1');
			exit;
		} catch (\PDOException $e) {
			Logger::error("Failed to delete employee via handler", [
				'error' => $e->getMessage(),
				'id' => $id
			]);
			if ($e->getCode() === '23000') {
				$errorMessage = 'Неможливо видалити співробітника: у нього є активні замовлення. Спочатку видаліть або перепризначте ці замовлення.';
			} else {
				$errorMessage = 'Помилка бази даних: ' . $e->getMessage();
			}

			$list = [];
			try { $list = $this->service->getAll(); } catch (\Throwable $_) {}

			View::render('employees/index', [
				'employees' => $list,
				'error'     => $errorMessage
			]);

		} catch (\Throwable $e) {
			Logger::error("Failed to delete employee via handler", [
				'error' => $e->getMessage(),
				'id' => $id
			]);
			$list = [];
			try {
				$list = $this->service->getAll();
			} catch (\Throwable $_) {
			}

			View::render('employees/index', [
				'employees' => $list,
				'error' => 'Сталася помилка: ' . $e->getMessage()
			]);
		}
	}
}


