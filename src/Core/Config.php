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
}