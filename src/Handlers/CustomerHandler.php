<?php

namespace Src\Handlers;

use Src\Services\CustomerService;

class CreateCustomerHandler
{
	private CustomerService $service;

	public function __construct()
	{
		$this->service = new CustomerService();
	}

	public function handle(): void
	{
		$message = null;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$firstName  = trim($_POST['firstName'] ?? '');
			$lastName   = trim($_POST['lastName'] ?? '');
			$city       = !empty($_POST['city']) ? trim($_POST['city']) : null;
			$street     = !empty($_POST['street']) ? trim($_POST['street']) : null;
			$zipCode    = !empty($_POST['zipCode']) ? trim($_POST['zipCode']) : null;

			try {
				$this->service->createCustomer($firstName, $lastName, $city, $street, $zipCode);
				$message = 'Customer created successfully!';
			} catch (\Exception $e) {
				$message = 'Error: ' . $e->getMessage();
			}
		}

		// 4. Подключаем View
		require __DIR__ . '/../../views/create_customer.php';
	}
}