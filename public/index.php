<?php

require __DIR__ . '/../vendor/autoload.php';

use Src\Core\View;
use Src\Handlers\CustomerHandler;
use Src\Core\Config;
use Src\Core\Database;
use Src\Handlers\EmployeeHandler;
use Src\Handlers\ItemHandler;
use Src\Handlers\OrderHandler;

try {
	$config = new Config(__DIR__ . '/../.env');
	$dbConfig = [
		'host'     => $config->get('DB_HOST'),
		'dbname'   => $config->get('DB_NAME'),
		'user'     => $config->get('DB_USER'),
		'password' => $config->get('DB_PASS'),
		'charset'  => $config->get('DB_CHARSET', 'utf8mb4'),
	];
	Database::getInstance($dbConfig);

$route = $_GET['r'] ?? 'home';

$customerHandler = new CustomerHandler();
$employeeHandler = new EmployeeHandler();
$itemHandler = new ItemHandler();
$orderHandler = new OrderHandler();
// 4. Маршрутизація
		switch ($route) {
		// --- READ (Перегляд) ---
		case 'customer/index':
			$customerHandler->index();
			break;

		case 'customer/show':
			$id = (int)($_GET['id'] ?? 0);
			$customerHandler->show($id);
			break;

		// --- CREATE (Створення) ---
		case 'customer/create':
			$customerHandler->create();
			break;

		case 'customer/store':
			$customerHandler->store();
			break;

		// --- UPDATE (Редагування) ---
		case 'customer/edit':
			$id = (int)($_GET['id'] ?? 0);
			$customerHandler->edit($id);
			break;

		case 'customer/update':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$customerHandler->update($id);
			break;

		// --- DELETE (Видалення) ---
		case 'customer/destroy':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$customerHandler->destroy($id);
			break;

		// --- EMPLOYEES ---
		case 'employee/index':
			$employeeHandler->index();
			break;
		case 'employee/show':
			$id = (int)($_GET['id'] ?? 0);
			$employeeHandler->show($id);
			break;
		case 'employee/create':
			$employeeHandler->create();
			break;
		case 'employee/store':
			$employeeHandler->store();
			break;
		case 'employee/edit':
			$id = (int)($_GET['id'] ?? 0);
			$employeeHandler->edit($id);
			break;
		case 'employee/update':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$employeeHandler->update($id);
			break;
		case 'employee/destroy':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$employeeHandler->destroy($id);
			break;

  // --- ITEMS ---
		case 'item/index':
			$itemHandler->index();
			break;
		case 'item/show':
			$id = (int)($_GET['id'] ?? 0);
			$itemHandler->show($id);
			break;
		case 'item/create':
			$itemHandler->create();
			break;
		case 'item/store':
			$itemHandler->store();
			break;
		case 'item/edit':
			$id = (int)($_GET['id'] ?? 0);
			$itemHandler->edit($id);
			break;
		case 'item/update':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$itemHandler->update($id);
			break;
		case 'item/destroy':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$itemHandler->destroy($id);
			break;

		// --- ORDERS ---
		case 'order/index':
			$orderHandler->index();
			break;
		case 'order/show':	
			$id = (int)($_GET['id'] ?? 0);
			$orderHandler->show($id);
			break;
		case 'order/create':
			$orderHandler->create();
			break;
		case 'order/store':
			$orderHandler->store();
			break;
		case 'order/edit':
			$id = (int)($_GET['id'] ?? 0);
			$orderHandler->edit($id);
			break;
		case 'order/update':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$orderHandler->update($id);
			break;
		case 'order/destroy':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$orderHandler->destroy($id);
			break;
		case 'order/addItem':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$orderHandler->addItem($id);
			break;
		case 'order/addItems':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$orderHandler->addItems($id);
			break;
		case 'order/removeItem':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$orderHandler->removeItem($id);
			break;

		// --- HOME / DEFAULT ---
		case 'home':
		default:
			View::render('home', [
				'title' => 'Панель керування',
			]);
			break;
	}

} catch (\Throwable $e) {
	error_log("Critical Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());

	http_response_code(500);
	echo "<h1>500 Internal Server Error</h1>";
	echo "<p>Сталася помилка на сервері. Спробуйте пізніше.</p>";
}