<?php

namespace Src\Core;

use PDO;
use PDOException;
use Src\Core\Config;

final class Database
{
	private static array $instances = [];
	private static ?array $currentConfig = null;
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
				'dbname' => $config['dbname'],
				'user' => $config['user']
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

	public static function getInstance(array $config = null): Database
	{
		if ($config !== null) {
			self::$currentConfig = $config;
		} elseif (self::$currentConfig === null) {
			
			self::$currentConfig = self::getDefaultConfig();
		}

		$config = self::$currentConfig;

		
		$key = md5(serialize([
			'host' => $config['host'],
			'dbname' => $config['dbname'],
			'user' => $config['user'],
			'charset' => $config['charset']
		]));

		if (!isset(self::$instances[$key])) {
			self::$instances[$key] = new self($config);
		}

		return self::$instances[$key];
	}

	public static function setConfig(array $config): void
	{
		self::$currentConfig = $config;
	}

	private static function getDefaultConfig(): array
	{
		
		try {
			$config = new Config(__DIR__ . '/../../.env');
			return [
				'host'     => $config->get('DB_HOST'),
				'dbname'   => $config->get('DB_NAME'),
				'user'     => $config->get('DB_USER'),
				'password' => $config->get('DB_PASS'),
				'charset'  => $config->get('DB_CHARSET', 'utf8mb4'),
			];
		} catch (\Exception $e) {
			throw new \RuntimeException("Не удалось загрузить конфигурацию БД: " . $e->getMessage());
		}
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


