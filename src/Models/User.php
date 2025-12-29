<?php

namespace Src\Models;

class User
{
	public function __construct(
		public string $username,
		public string $passwordHash,
		public string $role,
		public ?int $id,
		public ?string $createdAt
	) {}
}


