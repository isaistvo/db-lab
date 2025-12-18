<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Мій сайт' ?></title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        nav { border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        footer { margin-top: 50px; color: gray; }
    </style>
</head>
<body>

<nav>
    <a href="/">Головна</a> | <a href="/customer/create">Створити клієнта</a>
</nav>

<main>
	<?= $content ?>
</main>

<footer>
    &copy; 2025 Мій Проект
</footer>

</body>
</html>