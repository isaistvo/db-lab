<?php

declare(strict_types=1);

namespace Src\Handlers;

use Src\Services\CustomerService;
use Src\Core\View;
use Src\Core\Logger;
use Src\DTO\CreateCustomerDTO;
use Src\DTO\UpdateCustomerDTO;
use Src\Models\Customer;

class CustomerHandler
{
	private CustomerService $service;

	private const BASE_URL = '/db-lab/public/index.php';

	public function __construct()
	{
		$this->service = new CustomerService();
	}

	
	public function index(): void
	{
		try {
			$customers = $this->service->getAll();

			
			$message = null;
			if (isset($_GET['updated']) && $_GET['updated'] === '1') {
				$message = 'Клієнта успішно оновлено.';
			} elseif (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
				$message = 'Клієнта видалено.';
			}

			View::render('customers/index', [
				'customers' => $customers,
				'title'     => 'Список клієнтів',
				'message'   => $message,
			]);
		} catch (\Throwable $e) {
			Logger::error("Failed to load customer list", ['error' => $e->getMessage()]);
			View::render('customers/index', [
				'customers' => [],
				'error'     => 'Помилка завантаження списку: ' . $e->getMessage()
			]);
		}
	}

	
	public function create(): void
	{
		$viewData = [
			'form' => [] // Порожній масив для форми
		];

		if (isset($_GET['success']) && $_GET['success'] === '1') {
			$viewData['message'] = 'Клієнта успішно створено!';
		}

		View::render('customers/create', $viewData);
	}

	
	public function store(): void
	{
		try {
			$dto = CreateCustomerDTO::fromArray($_POST);
			$this->service->createCustomer($dto);

			Logger::info("Customer created via handler", [
				'first_name' => $dto->firstName,
				'last_name' => $dto->lastName
			]);

			header('Location: ' . self::BASE_URL . '?r=customer/create&success=1');
			exit;
		} catch (\Throwable $e) {
			Logger::error("Failed to create customer via handler", [
				'error' => $e->getMessage(),
				'input' => $_POST
			]);
			
			View::render('customers/create', [
				'error' => 'Помилка: ' . $e->getMessage(),
				'form'  => $_POST // Уніфікована назва змінної
			]);
		}
	}

	
	public function show(int $id): void
	{
		if ($id <= 0) {
			$this->render404();
			return;
		}

		try {
			$customer = $this->service->getById($id);
			if (!$customer) {
				$this->render404();
				return;
			}
			View::render('customers/show', ['customer' => $customer]);
		} catch (\Throwable $e) {
			View::render('customers/show', ['error' => $e->getMessage()]);
		}
	}

	
	public function edit(int $id): void
	{
		if ($id <= 0) {
			$this->render404();
			return;
		}

		try {
			$customer = $this->service->getById($id);
			if (!$customer) {
				$this->render404();
				return;
			}

			
			$formData = $this->mapCustomerToForm($customer);

			View::render('customers/edit', [
				'form' => $formData
			]);
		} catch (\Throwable $e) {
			View::render('customers/edit', ['error' => $e->getMessage()]);
		}
	}

	
	public function update(int $id): void
	{
		if ($id <= 0) {
			http_response_code(400);
			echo 'Некоректний ID';
			exit;
		}

		try {
			$dto = UpdateCustomerDTO::fromArray($_POST);
			
			$dto->id = $id;

			$this->service->updateCustomer($id, $dto);

			header('Location: ' . self::BASE_URL . '?r=customer/index&updated=1');
			exit;
		} catch (\Throwable $e) {
			
			$formData = $_POST;
			$formData['id'] = $id;

			View::render('customers/edit', [
				'error' => 'Помилка: ' . $e->getMessage(),
				'form'  => $formData,
			]);
		}
	}

	
	public function destroy(int $id): void
	{
		if ($id <= 0) {
			http_response_code(400);
			echo 'Некоректний ID';
			exit;
		}

		try {
			$this->service->deleteCustomer($id);
			header('Location: ' . self::BASE_URL . '?r=customer/index&deleted=1');
			exit;
		} catch (\Throwable $e) {
			$customers = [];
			try {
				$customers = $this->service->getAll();
			} catch (\Throwable $_) {}

			View::render('customers/index', [
				'customers' => $customers,
				'error'     => 'Помилка видалення: ' . $e->getMessage()
			]);
		}
	}

	
	private function mapCustomerToForm(Customer $customer): array
	{
		return [
			'id'        => $customer->id,
			'firstName' => $customer->firstName,
			'lastName'  => $customer->lastName,
			'city'      => $customer->city,
			'street'    => $customer->street,
			'zipCode'   => $customer->zipCode,
		];
	}

	
	private function render404(): void
	{
		http_response_code(404);
		View::render('customers/index', ['error' => 'Клієнта не знайдено']);
	}
}


