<?php

namespace Src\DTO;

class UpdateEmployeeDTO
{
	public int $id;
	public string $firstName;
	public string $lastName;
	public ?string $city;
	public ?string $street;
	public ?string $zipCode;

	public function __construct(
		int $id,
		string $firstName,
		string $lastName,
		?string $city = null,
		?string $street = null,
		?string $zipCode = null
	) {
		$this->id = $id;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->city = $city;
		$this->street = $street;
		$this->zipCode = $zipCode;
	}

	public static function fromArray(array $data): self
	{
		return new self(
			(int)($data['id'] ?? 0),
			trim((string)($data['firstName'] ?? '')),
			trim((string)($data['lastName'] ?? '')),
			$data['city'] ?? null,
			$data['street'] ?? null,
			$data['zipCode'] ?? null
		);
	}
}