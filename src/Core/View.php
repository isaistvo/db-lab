<?php

namespace Src\Core;

class View
{
	public static function render(string $viewPath, array $data = [], string $layoutName = 'main'): void
	{
		extract($data);
		ob_start();
		require __DIR__ . "/../../views/$viewPath.php";
		$content = ob_get_clean();
		require __DIR__ . "/../../views/layouts/$layoutName.php";
	}
}