<?php

namespace Src\Core;

use PDO;

abstract class Repository
{
	protected PDO $db;

	public function __construct()
	{
		$this->db = Database::getInstance()->getConnection();
	}
}