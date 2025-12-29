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
		$firstName = trim((string)($data['firstName'] ?? ''));
		$lastName = trim((string)($data['lastName'] ?? ''));
		$city = !empty($data['city']) ? trim((string)$data['city']) : null;
		$street = !empty($data['street']) ? trim((string)$data['street']) : null;
		$zipCode = !empty($data['zipCode']) ? trim((string)$data['zipCode']) : null;

		
		$errors = [];
		if (empty($firstName)) {
			$errors[] = 'Ім\'я обов\'язкове';
		} elseif (strlen($firstName) > 100) {
			$errors[] = 'Ім\'я має містити не більше 100 символів';
		}

		if (empty($lastName)) {
			$errors[] = 'Прізвище обов\'язкове';
		} elseif (strlen($lastName) > 100) {
			$errors[] = 'Прізвище має містити не більше 100 символів';
		}

		if ($city !== null && strlen($city) > 100) {
			$errors[] = 'Місто має містити не більше 100 символів';
		}

		if ($street !== null && strlen($street) > 255) {
			$errors[] = 'Вулиця має містити не більше 255 символів';
		}

		if ($zipCode !== null && !preg_match('/^\d{5}$/', $zipCode)) {
			$errors[] = 'Поштовий індекс має бути 5 цифр';
		}

		if (!empty($errors)) {
			throw new \InvalidArgumentException(implode('; ', $errors));
		}

		return new self(
			firstName: $firstName,
			lastName: $lastName,
			city: $city,
			street: $street,
			zipCode: $zipCode
		);
	}
}


