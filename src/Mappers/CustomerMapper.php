<?php

namespace Src\Mappers;

use Src\DTO\CreateCustomerDTO;
use Src\DTO\UpdateCustomerDTO;
use Src\Models\Customer;

class CustomerMapper
{
	public static function fromCreateDTO(CreateCustomerDTO $dto): Customer
	{
		return new Customer(
			id: null,
			firstName: $dto->firstName,
			lastName: $dto->lastName,
			city: $dto->city,
			street: $dto->street,
			zipCode: $dto->zipCode
		);
	}

	public static function fromUpdateDTO(UpdateCustomerDTO $dto): Customer
	{
		return new Customer(
			id: $dto->id,
			firstName: $dto->firstName,
			lastName: $dto->lastName,
			city: $dto->city,
			street: $dto->street,
			zipCode: $dto->zipCode
		);
	}

	public static function fromDBRow(array $row): Customer
	{
		return new Customer(
			id: (int)($row['CustomerID'] ?? $row['id']),
			firstName: $row['FirstName'] ?? $row['first_name'],
			lastName: $row['LastName'] ?? $row['last_name'],
			city: $row['City'] ?? $row['city'],
			street: $row['Street'] ?? $row['street'],
			zipCode: $row['ZipCode'] ?? $row['zip_code']
		);
	}
}