<?php

namespace Src\Repositories;

use PDO;
use Src\Core\Database;
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
			return null;
		}
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
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM customer WHERE CustomerID = :id");
        $stmt->execute(['id' => $id]);
    }

}