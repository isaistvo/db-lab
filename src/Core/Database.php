<?php

namespace Src\Core;

use PDO;
use PDOException;

final class Database
{
	private static ?Database $instance = null;
	private PDO $connection;

	private function __construct(array $config)
	{
		$dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

		try {
			$this->connection = new PDO($dsn, $config['user'], $config['password']);

			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			Logger::info("Database connection established successfully", [
				'host' => $config['host'],
				'dbname' => $config['dbname']
			]);

		} catch (PDOException $e) {
			Logger::error("Database connection failed", [
				'error' => $e->getMessage(),
				'host' => $config['host'],
				'dbname' => $config['dbname']
			]);
			throw new PDOException("Помилка підключення до БД: " . $e->getMessage(), (int)$e->getCode());
		}
	}

	public static function getInstance(?array $config = null): Database
	{
		if (self::$instance === null) {
			if ($config === null) {
				throw new \RuntimeException("База даних не ініціалізована. Передайте конфігурацію при першому виклику.");
			}
			self::$instance = new self($config);
		}
		return self::$instance;
	}

	public function getConnection(): PDO
	{
		return $this->connection;
	}

	private function __clone() {}

	public function __wakeup()
	{
		throw new \Exception("Cannot unserialize a singleton.");
	}
}