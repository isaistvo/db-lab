<?php

namespace Src\Services;

use Src\Repositories\CustomerRepository;
use Src\Models\Customer;

class CustomerService
{
	private CustomerRepository $repository;

	public function __construct()
	{
		$this->repository = new CustomerRepository();
	}

	public function createCustomer(string $firstName, string $lastName, ?string $city, ?string $street, ?string $zipCode): void
	{
		if (empty(trim($firstName)) || empty(trim($lastName))) {
			throw new \InvalidArgumentException("First name and last name are required.");
		}
		$customer = new Customer(null, $firstName, $lastName, $city, $street, $zipCode);
		$this->repository->save($customer);
	}
}