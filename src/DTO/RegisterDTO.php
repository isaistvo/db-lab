<?php

namespace Src\DTO;

readonly class RegisterDTO
{
	public function __construct(
		public string $username,
		public string $password,
		public string $role
	) {}

	public static function fromArray(array $data): self
	{
		$username = trim((string)($data['username'] ?? ''));
		$password = trim((string)($data['password'] ?? ''));
		$role = trim((string)($data['role'] ?? 'employee'));

		
		$errors = [];
		if (empty($username)) {
			$errors[] = 'Логін обов\'язковий';
		} elseif (strlen($username) < 3 || strlen($username) > 50) {
			$errors[] = 'Логін має містити від 3 до 50 символів';
		} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
			$errors[] = 'Логін може містити тільки букви, цифри та підкреслення';
		}

		if (empty($password)) {
			$errors[] = 'Пароль обов\'язковий';
		} elseif (strlen($password) < 8) {
			$errors[] = 'Пароль має містити не менше 8 символів';
		}

		$allowedRoles = ['admin', 'employee', 'customer'];
		if (!in_array($role, $allowedRoles, true)) {
			$errors[] = 'Роль має бути однією з: ' . implode(', ', $allowedRoles);
		}

		if (!empty($errors)) {
			throw new \InvalidArgumentException(implode('; ', $errors));
		}

		return new self(
			username: $username,
			password: $password,
			role: $role
		);
	}
}


