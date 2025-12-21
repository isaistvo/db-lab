<?php

declare(strict_types=1);

namespace Src\Handlers;

use Src\Services\CustomerService;
use Src\Core\View;
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

	/**
	 * GET /customer/index
	 * Відображення списку всіх клієнтів.
	 */
	public function index(): void
	{
		try {
			$customers = $this->service->getAll();

			// Обробка повідомлень із GET-параметрів
			$message = null;
			if (isset($_GET['updated'])) {
				$message = 'Клієнта успішно оновлено.';
			} elseif (isset($_GET['deleted'])) {
				$message = 'Клієнта видалено.';
			}

			View::render('customers/index', [
				'customers' => $customers,
				'title'     => 'Список клієнтів',
				'message'   => $message,
			]);
		} catch (\Throwable $e) {
			View::render('customers/index', [
				'customers' => [],
				'error'     => 'Помилка завантаження списку: ' . $e->getMessage()
			]);
		}
	}

	/**
	 * GET /customer/create
	 * Відображення форми створення нового клієнта.
	 */
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

	/**
	 * POST /customer/store
	 * Обробка запиту на створення клієнта.
	 */
	public function store(): void
	{
		try {
			$dto = CreateCustomerDTO::fromArray($_POST);
			$this->service->createCustomer($dto);

			header('Location: ' . self::BASE_URL . '?r=customer/create&success=1');
			exit;
		} catch (\Throwable $e) {
			// У разі помилки повертаємо введені дані назад у форму
			View::render('customers/create', [
				'error' => 'Помилка: ' . $e->getMessage(),
				'form'  => $_POST // Уніфікована назва змінної
			]);
		}
	}

	/**
	 * GET /customer/show&id={id}
	 * Перегляд інформації про одного клієнта.
	 */
	public function show(int $id): void
	{
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

	/**
	 * GET /customer/edit&id={id}
	 * Відображення форми редагування клієнта.
	 */
	public function edit(int $id): void
	{
		try {
			$customer = $this->service->getById($id);
			if (!$customer) {
				$this->render404();
				return;
			}

			// Використовуємо допоміжний метод для підготовки даних форми
			$formData = $this->mapCustomerToForm($customer);

			View::render('customers/edit', [
				'form' => $formData
			]);
		} catch (\Throwable $e) {
			View::render('customers/edit', ['error' => $e->getMessage()]);
		}
	}

	/**
	 * POST /customer/update&id={id}
	 * Обробка оновлення даних клієнта.
	 */
	public function update(int $id): void
	{
		try {
			$dto = UpdateCustomerDTO::fromArray($_POST);
			// Примусово встановлюємо ID з маршруту (захист від підміни ID у формі)
			$dto->id = $id;

			$this->service->updateCustomer($id, $dto);

			header('Location: ' . self::BASE_URL . '?r=customer/index&updated=1');
			exit;
		} catch (\Throwable $e) {
			// У разі помилки повертаємо те, що надіслав користувач + ID
			$formData = $_POST;
			$formData['id'] = $id;

			View::render('customers/edit', [
				'error' => 'Помилка: ' . $e->getMessage(),
				'form'  => $formData,
			]);
		}
	}

	/**
	 * POST /customer/destroy&id={id}
	 * Видалення клієнта.
	 */
	public function destroy(int $id): void
	{
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

	/**
	 * Допоміжний метод: Перетворює об'єкт Customer на масив для форми.
	 */
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

	/**
	 * Допоміжний метод: Рендер помилки 404 (клієнта не знайдено).
	 */
	private function render404(): void
	{
		http_response_code(404);
		View::render('customers/index', ['error' => 'Клієнта не знайдено']);
	}
}