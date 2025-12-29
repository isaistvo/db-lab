<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

use Src\Core\View;
use Src\Handlers\CustomerHandler;
use Src\Core\Config;
use Src\Core\Database;
use Src\Core\Logger;
use Src\Handlers\EmployeeHandler;
use Src\Handlers\ItemHandler;
use Src\Handlers\OrderHandler;
use Src\Handlers\AuthHandler;

try {
	$config = new Config(__DIR__ . '/../.env');
	
	
	$userRole = $_SESSION['role'] ?? null;
	$dbConfig = $config->getDbConfigForRole($userRole);
	
	Database::getInstance($dbConfig);

$route = $_GET['r'] ?? 'home';


if (!preg_match('/^[a-zA-Z0-9\/_-]+$/', $route)) {
    http_response_code(400);
    echo 'Некоректний маршрут';
    exit;
}

Logger::info("Route accessed", ['route' => $route]);

$customerHandler = new CustomerHandler();
$employeeHandler = new EmployeeHandler();
$itemHandler = new ItemHandler();
$orderHandler = new OrderHandler();
$authHandler = new AuthHandler();



$publicRoutes = ['auth/login', 'auth/authenticate', 'auth/register', 'auth/store'];
if (!isset($_SESSION['user_id']) && !in_array($route, $publicRoutes)) {
    header('Location: /db-lab/public/index.php?r=auth/login');
    exit;
}


$userRole = $_SESSION['role'] ?? null;
$rolePermissions = [
    'admin' => ['customer', 'employee', 'item', 'order', 'home', 'auth'],
    'employee' => ['customer', 'item', 'order', 'home', 'auth'],
    'customer' => ['item', 'home', 'auth']
];

if (isset($_SESSION['user_id']) && $userRole && !in_array($route, $publicRoutes)) {
    $routePrefix = explode('/', $route)[0];
    if (!in_array($routePrefix, $rolePermissions[$userRole] ?? [])) {
        http_response_code(403);
        echo "<h1>403 Forbidden</h1>";
        echo "<p>У вас немає доступу до цієї сторінки.</p>";
        echo "<a href='/db-lab/public/index.php?r=home'>Повернутися на головну</a>";
        exit;
    }
}

		switch ($route) {
		
		case 'auth/login':
			$authHandler->login();
			break;
		case 'auth/register':
			$authHandler->register();
			break;
		case 'auth/authenticate':
			$authHandler->authenticate();
			break;
		case 'auth/store':
			$authHandler->store();
			break;
		case 'auth/logout':
			$authHandler->logout();
			break;
		
		case 'customer/index':
			$customerHandler->index();
			break;

		case 'customer/show':
			$id = (int)($_GET['id'] ?? 0);
			$customerHandler->show($id);
			break;

		
		case 'customer/create':
			$customerHandler->create();
			break;

		case 'customer/store':
			$customerHandler->store();
			break;

		
		case 'customer/edit':
			$id = (int)($_GET['id'] ?? 0);
			$customerHandler->edit($id);
			break;

		case 'customer/update':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$customerHandler->update($id);
			break;

		
		case 'customer/destroy':
			$id = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
			$customerHandler->destroy($id);
			break;

		
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

		
		case 'home':
		default:
			View::render('home', [
				'title' => 'Панель керування',
			]);
			break;
	}

} catch (\Throwable $e) {
	Logger::critical("Critical error in main application", [
		'error' => $e->getMessage(),
		'file' => $e->getFile(),
		'line' => $e->getLine(),
		'trace' => $e->getTraceAsString()
	]);

	http_response_code(500);
	echo "<h1>500 Internal Server Error</h1>";
	echo "<p>Сталася помилка на сервері. Спробуйте пізніше.</p>";
}
