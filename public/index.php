<?php

declare(strict_types=1);
require_once __DIR__ . '/../src/Core/Autoload.php';
Src\Core\Autoload::register();
use Src\Core\Database;

$db = Database::getInstance()->getConnection();
echo "db connected";