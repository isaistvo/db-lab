<?php

declare(strict_types=1);

namespace Src\Handlers;

use Src\Services\AuthService;
use Src\Core\View;
use Src\Core\Logger;
use Src\DTO\LoginDTO;
use Src\DTO\RegisterDTO;
use Src\Core\Config;
use Src\Core\Database;

class AuthHandler
{
	private AuthService $service;

	private const BASE_URL = '/db-lab/public/index.php';

	public function __construct()
	{
		$this->service = new AuthService();
	}

	
	public function login(): void
	{
		try {
			
			if (isset($_SESSION['user_id'])) {
				header('Location: ' . self::BASE_URL . '?r=home');
				exit;
			}

			$error = $_GET['error'] ?? null;

			View::render('auth/login', [
				'title' => 'Вхід в систему',
				'error' => $error,
			]);
		} catch (\Throwable $e) {
			Logger::error("Failed to load login page", ['error' => $e->getMessage()]);
			View::render('auth/login', [
				'title' => 'Вхід в систему',
				'error' => 'Сталася помилка при завантаженні сторінки.',
			]);
		}
	}

	
	public function register(): void
	{
		try {
			
			if (isset($_SESSION['user_id'])) {
				header('Location: ' . self::BASE_URL . '?r=home');
				exit;
			}

			$error = $_GET['error'] ?? null;

			View::render('auth/register', [
				'title' => 'Реєстрація',
				'error' => $error,
			]);
		} catch (\Throwable $e) {
			Logger::error("Failed to load register page", ['error' => $e->getMessage()]);
			View::render('auth/register', [
				'title' => 'Реєстрація',
				'error' => 'Сталася помилка при завантаженні сторінки.',
			]);
		}
	}

	
	public function authenticate(): void
	{
		try {
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				header('Location: ' . self::BASE_URL . '?r=auth/login');
				exit;
			}

			$input = LoginDTO::fromArray($_POST);

			Logger::info("Login attempt", ['username' => $input->username]);

			$user = $this->service->authenticate($input);
			if (!$user) {
				header('Location: ' . self::BASE_URL . '?r=auth/login&error=Невірний логін або пароль');
				exit;
			}

			
			if (session_status() === PHP_SESSION_NONE) {
				session_start();
			}

			$_SESSION['user_id'] = $user->id;
			$_SESSION['username'] = $user->username;
			$_SESSION['role'] = $user->role;

			Logger::info("User logged in", ['username' => $user->username]);

			
			$config = new Config(__DIR__ . '/../../.env');
			$dbConfig = $config->getDbConfigForRole($user->role);
			Database::setConfig($dbConfig);

			header('Location: ' . self::BASE_URL . '?r=home');
			exit;
		} catch (\Throwable $e) {
			Logger::error("Authentication failed", ['error' => $e->getMessage()]);
			header('Location: ' . self::BASE_URL . '?r=auth/login&error=Сталася помилка при авторизації');
			exit;
		}
	}

	
	public function store(): void
	{
		try {
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				header('Location: ' . self::BASE_URL . '?r=auth/register');
				exit;
			}

			$input = RegisterDTO::fromArray($_POST);

			$this->service->registerUser($input);

			Logger::info("User registered successfully", ['username' => $input->username]);

			header('Location: ' . self::BASE_URL . '?r=auth/login&message=Реєстрація успішна. Тепер увійдіть в систему.');
			exit;
		} catch (\InvalidArgumentException $e) {
			Logger::info("Registration failed: validation error", ['error' => $e->getMessage()]);
			header('Location: ' . self::BASE_URL . '?r=auth/register&error=' . urlencode($e->getMessage()));
			exit;
		} catch (\Throwable $e) {
			Logger::error("Registration failed", ['error' => $e->getMessage()]);
			header('Location: ' . self::BASE_URL . '?r=auth/register&error=Сталася помилка при реєстрації');
			exit;
		}
	}

	
	public function logout(): void
	{
		try {
			if (session_status() === PHP_SESSION_NONE) {
				session_start();
			}

			$username = $_SESSION['username'] ?? 'unknown';

			
			$_SESSION = [];
			session_destroy();
			
			
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time() - 3600, '/');
			}

			Logger::info("User logged out", ['username' => $username]);

			
			$config = new Config(__DIR__ . '/../../.env');
			$dbConfig = $config->getDbConfigForRole(null);
			Database::setConfig($dbConfig);

			header('Location: ' . self::BASE_URL . '?r=auth/login');
			exit;
		} catch (\Throwable $e) {
			Logger::error("Logout failed", ['error' => $e->getMessage()]);
			header('Location: ' . self::BASE_URL . '?r=auth/login');
			exit;
		}
	}
}


