<?php

namespace Src\Services;

use Src\Repositories\CustomerRepository;
use Src\DTO\CreateCustomerDTO;
use Src\DTO\UpdateCustomerDTO;
use Src\Mappers\CustomerMapper;
use Src\Models\Customer;
use Src\Core\Logger;

class CustomerService
{
    private CustomerRepository $repository;

    public function __construct()
    {
        $this->repository = new CustomerRepository();
    }

    public function createCustomer(CreateCustomerDTO $input): void
    {
        if (empty($input->firstName) || empty($input->lastName)) {
            throw new \InvalidArgumentException("First name and last name are required.");
        }
        $customer = CustomerMapper::fromCreateDTO($input);
        $this->repository->save($customer);
		Logger::info("Customer created", [
			'first_name' => $input->firstName,
			'last_name' => $input->lastName
		]);
    }

    
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?Customer
    {
        return $this->repository->findById($id);
    }

    public function updateCustomer(int $id, UpdateCustomerDTO $input): void
    {
        if (empty($input->firstName) || empty($input->lastName)) {
            throw new \InvalidArgumentException("First name and last name are required.");
        }

        $existing = $this->repository->findById($id);
        if (!$existing) {
            throw new \RuntimeException('Customer not found');
        }

	    $updatedCustomer = CustomerMapper::fromUpdateDTO($input);

        $this->repository->update($updatedCustomer);
		Logger::info("Customer updated", ['customer_id' => $id]);
    }

    public function deleteCustomer(int $id): void
    {
        
        $existing = $this->repository->findById($id);
        if (!$existing) {
            throw new \RuntimeException('Customer not found');
        }
        $this->repository->delete($id);
		Logger::info("Customer deleted", ['customer_id' => $id]);
    }
}


