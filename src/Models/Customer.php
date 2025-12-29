<?php

namespace Src\Models;

readonly class Customer
{
	public function __construct(
		public string $firstName,
		public string $lastName,
		public ?string $city,
		public ?string $street,
		public ?string $zipCode,
		public ?int $id
	) {}
}


