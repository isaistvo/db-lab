<?php

namespace Src\Repositories;

use PDO;
use Src\Core\Database;
use Src\Core\Logger;
use Src\Models\User;
use Src\Mappers\UserMapper;

class UserRepository
{
    protected PDO $db;

	public function __construct(Database $db = null)
	{
		$this->db = $db ? $db->getConnection() : Database::getInstance()->getConnection();
	}

	public function findByUsername(string $username): ?User
	{
		$sql = "SELECT * FROM users WHERE Username = :username";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['username' => $username]);

		$row = $stmt->fetch();

		if (!$row) {
			Logger::info("User not found", ['username' => $username]);
			return null;
		}
		Logger::debug("User found", ['username' => $username]);
		return UserMapper::fromDBRow($row);
	}

	public function findById(int $id): ?User
	{
		$sql = "SELECT * FROM users WHERE UserID = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(['id' => $id]);

		$row = $stmt->fetch();

		if (!$row) {
			Logger::info("User not found", ['user_id' => $id]);
			return null;
		}
		Logger::debug("User found", ['user_id' => $id]);
		return UserMapper::fromDBRow($row);
	}

	public function save(User $user): void
	{
		if ($user->id === null) {
			
			$sql = "INSERT INTO users (Username, PasswordHash, Role) VALUES (:username, :password_hash, :role)";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				'username' => $user->username,
				'password_hash' => $user->passwordHash,
				'role' => $user->role
			]);
			$user->id = (int)$this->db->lastInsertId();
		} else {
			
			$sql = "UPDATE users SET Username = :username, PasswordHash = :password_hash, Role = :role WHERE UserID = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				'id' => $user->id,
				'username' => $user->username,
				'password_hash' => $user->passwordHash,
				'role' => $user->role
			]);
		}
		Logger::info("User saved", ['user_id' => $user->id, 'username' => $user->username]);
	}
}


