<?php

namespace Src\Repositories;

use Src\Core\Database;
use Src\Core\Logger;
use Src\Models\Employee;
use Src\Mappers\EmployeeMapper;
use PDO;

class EmployeeRepository
{
	private PDO $db;

	public function __construct()
	{
		$this->db = Database::getInstance()->getConnection();
	}

	public function save(Employee $employee): void
	{
		$stmt = $this->db->prepare("
            INSERT INTO Employees (FirstName, LastName, City, Street, ZipCode) 
            VALUES (:firstName, :lastName, :city, :street, :zipCode)
        ");
		$stmt->execute([
			':firstName' => $employee->firstName,
			':lastName' => $employee->lastName,
			':city' => $employee->city,
			':street' => $employee->street,
			':zipCode' => $employee->zipCode,
		]);
		Logger::info("Employee saved", [
			'first_name' => $employee->firstName,
			'last_name' => $employee->lastName
		]);
	}

	public function update(Employee $employee): void
	{
		$stmt = $this->db->prepare("
            UPDATE Employees 
            SET FirstName = :firstName, LastName = :lastName, City = :city, Street = :street, ZipCode = :zipCode
            WHERE EmployeeID = :id
        ");
		$stmt->execute([
			':id' => $employee->id,
			':firstName' => $employee->firstName,
			':lastName' => $employee->lastName,
			':city' => $employee->city,
			':street' => $employee->street,
			':zipCode' => $employee->zipCode,
		]);
		Logger::info("Employee updated", ['employee_id' => $employee->id]);
	}

	public function delete(int $id): void
	{
		$stmt = $this->db->prepare("DELETE FROM Employees WHERE EmployeeID = :id");
		$stmt->execute([':id' => $id]);
		Logger::info("Employee deleted", ['employee_id' => $id]);
	}

	public function findAll(): array
	{
		$stmt = $this->db->query("SELECT * FROM Employees ORDER BY EmployeeID DESC");
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return array_map(fn($row) => EmployeeMapper::fromDBRow($row), $rows);
	}

	public function findById(int $id): ?Employee
	{
		$stmt = $this->db->prepare("SELECT * FROM Employees WHERE EmployeeID = :id LIMIT 1");
		$stmt->execute([':id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$row) {
			Logger::info("Employee not found", ['employee_id' => $id]);
			return null;
		}
		Logger::debug("Employee found", ['employee_id' => $id]);
		return $row ? EmployeeMapper::fromDBRow($row) : null;
	}
}


