<?php

namespace Src\Repositories;

use PDO;
use Src\Core\Database;
use Src\Core\Logger;
use Src\Models\Customer;
use Src\Mappers\CustomerMapper;

class CustomerRepository
{
    protected PDO $db;

	public function __construct()
	{
		$this->db = Database::getInstance()->getConnection();
	}

 public function findById(int $id): ?Customer
 {
     $sql = "SELECT * FROM customer WHERE CustomerID = :id";
     $stmt = $this->db->prepare($sql);
     $stmt->execute(['id' => $id]);

		$row = $stmt->fetch();

		if (!$row) {
			Logger::info("Customer not found", ['customer_id' => $id]);
			return null;
		}
		Logger::debug("Customer found", ['customer_id' => $id]);
        return CustomerMapper::fromDBRow($row);
    }

    /**
     * New alias per requirements: findAll()
     * @return Customer[]
     */
    public function findAll(): array
    {
        return $this->getAll();
    }

    /**
     * @return Customer[]
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM customer ORDER BY CustomerID DESC";
        $stmt = $this->db->query($sql);
        $customers = [];
        while ($row = $stmt->fetch()) {
            // Используем маппер для каждой строки
            $customers[] = CustomerMapper::fromDBRow($row);
        }
        return $customers;
    }

    public function save(Customer $customer): void
    {
        $sql = "INSERT INTO customer (FirstName, LastName, City, Street, ZipCode) 
                VALUES (:fname, :lname, :city, :street, :zip)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fname' => $customer->firstName,
            'lname' => $customer->lastName,
            'city' => $customer->city,
            'street' => $customer->street,
            'zip' => $customer->zipCode,
        ]);
		Logger::info("Customer saved", [
			'first_name' => $customer->firstName,
			'last_name' => $customer->lastName
		]);
    }

    public function update(Customer $customer): void
    {
        $sql = "UPDATE customer 
                SET FirstName = :fname, LastName = :lname, City = :city, Street = :street, ZipCode = :zip 
                WHERE CustomerID = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fname' => $customer->firstName,
            'lname' => $customer->lastName,
            'city' => $customer->city,
            'street' => $customer->street,
            'zip' => $customer->zipCode,
            'id' => $customer->id,
        ]);
		Logger::info("Customer updated", ['customer_id' => $customer->id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM customer WHERE CustomerID = :id");
        $stmt->execute(['id' => $id]);
		Logger::info("Customer deleted", ['customer_id' => $id]);
    }

}