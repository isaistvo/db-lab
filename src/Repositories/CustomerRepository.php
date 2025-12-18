<?php

namespace Src\Repositories;

use Src\Core\Repository;
use Src\Models\Customer;

class CustomerRepository extends Repository
{
	/**
	 * Пошук клієнта за ID
	 */
	public function findById(int $id): ?Customer
	{
		$sql = "SELECT * FROM customer WHERE CustomerID = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['id' => $id]);

		$row = $stmt->fetch();

		if (!$row) {
			return null;
		}

		return $this->mapToEntity($row);
	}

	/**
	 * Отримати всіх клієнтів
	 */
	public function getAll(): array
	{
		$sql = "SELECT * FROM customer ORDER BY CustomerID DESC";
		$stmt = $this->db->query($sql);

		$customers = [];
		while ($row = $stmt->fetch()) {
			$customers[] = $this->mapToEntity($row);
		}

		return $customers;
	}

	/**
	 * Зберегти нового клієнта (INSERT)
	 */
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

		// Якщо потрібно отримати ID новоствореного запису:
		// $id = $this->db->lastInsertId();
	}

	/**
	 * Оновити дані клієнта (UPDATE)
	 */
	public function update(Customer $customer): void
	{
		$sql = "UPDATE customer 
                SET FirstName = :fname, 
                    LastName = :lname, 
                    City = :city, 
                    Street = :street, 
                    ZipCode = :zip 
                WHERE CustomerID = :id";

		$stmt = $this->db->prepare($sql);

		$stmt->execute([
			'id' => $customer->id,
			'fname' => $customer->firstName,
			'lname' => $customer->lastName,
			'city' => $customer->city,
			'street' => $customer->street,
			'zip' => $customer->zipCode,
		]);
	}

	/**
	 * Видалити клієнта (DELETE)
	 */
	public function delete(int $id): void
	{
		$sql = "DELETE FROM customer WHERE CustomerID = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['id' => $id]);
	}

	private function mapToEntity(array $row): Customer
	{
		return new Customer(
			id: (int)$row['CustomerID'],
			firstName: $row['FirstName'],
			lastName: $row['LastName'],
			city: $row['City'],
			street: $row['Street'],
			zipCode: $row['ZipCode']
		);
	}
}