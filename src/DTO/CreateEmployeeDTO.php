<?php

namespace Src\DTO;

class CreateEmployeeDTO
{
	public function __construct(
		public string $firstName,
		public string $lastName,
		public ?string $city,
		public ?string $street,
		public ?string $zipCode
	) {}

	public static function fromArray(array $data): self
	{
		return new self(
			trim((string)($data['firstName'] ?? '')),
			trim((string)($data['lastName'] ?? '')),
			$data['city'] ?? null,
			$data['street'] ?? null,
			$data['zipCode'] ?? null
		);
	}
}