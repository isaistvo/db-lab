<?php

namespace Src\DTO;

readonly class CreateCustomerDTO
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
		$filter = fn($key) => !empty($data[$key]) && trim($data[$key]) !== ''
			? trim($data[$key])
			: null;

		return new self(
			firstName: trim((string)($data['firstName'] ?? '')),
			lastName:  trim((string)($data['lastName'] ?? '')),
			city:      $filter('city'),
			street:    $filter('street'),
			zipCode:   $filter('zipCode')
		);
	}
}