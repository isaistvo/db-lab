<?php

namespace Src\Mappers;

use Src\DTO\RegisterDTO;
use Src\Models\User;

class UserMapper
{
	public static function fromDBRow(array $row): User
	{
		return new User(
			id: (int)($row['UserID'] ?? $row['id']),
			username: $row['Username'] ?? $row['username'],
			passwordHash: $row['PasswordHash'] ?? $row['password_hash'],
			role: $row['Role'] ?? $row['role'],
			createdAt: $row['CreatedAt'] ?? $row['created_at']
		);
	}

	public static function fromRegisterDTO(RegisterDTO $dto): User
	{
		return new User(
			id: null,
			username: $dto->username,
			passwordHash: password_hash($dto->password, PASSWORD_DEFAULT),
			role: $dto->role,
			createdAt: null
		);
	}
}


