<?php

namespace Src\Mappers;

use Src\DTO\CreateEmployeeDTO;
use Src\DTO\UpdateEmployeeDTO;
use Src\Models\Employee;

class EmployeeMapper
{
	public static function fromCreateDTO(CreateEmployeeDTO $dto): Employee
	{
		return new Employee(null, $dto->firstName, $dto->lastName, $dto->city, $dto->street, $dto->zipCode);
	}

	public static function fromUpdateDTO(UpdateEmployeeDTO $dto): Employee
	{
		return new Employee($dto->id, $dto->firstName, $dto->lastName, $dto->city, $dto->street, $dto->zipCode);
	}

	public static function fromDBRow(array $row): Employee
	{
		return new Employee(
			id: (int)($row['EmployeeID'] ?? $row['id']), // Враховуємо специфіку таблиці Employees
			firstName: $row['FirstName'],
			lastName: $row['LastName'],
			city: $row['City'],
			street: $row['Street'],
			zipCode: $row['ZipCode']
		);
	}

	public static function toFormArray(Employee $employee): array
	{
		return [
			'id'        => $employee->id,
			'firstName' => $employee->firstName,
			'lastName'  => $employee->lastName,
			'city'      => $employee->city,
			'street'    => $employee->street,
			'zipCode'   => $employee->zipCode,
		];
	}
}