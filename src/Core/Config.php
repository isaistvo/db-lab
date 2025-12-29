<?php

namespace Src\Core;

use Exception;

class Config
{
	private array $settings;

	public function __construct(string $path = __DIR__ . '/../../.env')
	{
		if (!file_exists($path)) {
			throw new Exception("Файл конфігурації не знайдено: $path");
		}

		$parsed = parse_ini_file($path);

		if ($parsed === false) {
			throw new Exception("Не вдалося розпарсити файл конфігурації");
		}

		$this->settings = $parsed;
	}

	public function get(string $key, mixed $default = null): mixed
	{
		return $this->settings[$key] ?? $default;
	}

	
	public function getDbConfigForRole(?string $role): array
	{
		$baseConfig = [
			'host'     => $this->get('DB_HOST'),
			'dbname'   => $this->get('DB_NAME'),
			'charset'  => $this->get('DB_CHARSET', 'utf8mb4'),
		];
		
		$dbCredentials = [
			'admin' => ['user' => 'admin', 'password' => 'A!dm1n'],      // Повний доступ
			'employee' => ['user' => 'analyst', 'password' => 'An@lyt1c'], // SELECT на все
			'customer' => ['user' => 'guest', 'password' => 'Gu3st!'],     // SELECT тільки на items
			'default' => ['user' => 'admin', 'password' => 'A!dm1n'] // Для неавторизованих - admin права
		];
		
		$credentials = $dbCredentials[$role] ?? $dbCredentials['default'];
		
		return array_merge($baseConfig, $credentials);
	}
}


