<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Виделка' ?></title>

    <link rel="stylesheet" href="/db-lab/public/css/style.css">
</head>
<body>

<div class="container">
    <nav>
        <strong>Панель керування</strong>
        <span style="margin-left: auto; font-size: 0.9em; color: #6b7280;">
            <?php if (isset($_SESSION['username'])): ?>
                Користувач: <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8') ?>)
                <a href="/db-lab/public/index.php?r=auth/logout" class="btn btn-small">Вийти</a>
            <?php else: ?>
                <a href="/db-lab/public/index.php?r=auth/login" class="btn btn-small">Увійти</a>
            <?php endif; ?>
        </span>
    </nav>

    <div class="content-wrapper">

        <aside class="sidebar">
            <h3>Меню</h3>
            <ul class="nav-menu">
                <?php
                $userRole = $_SESSION['role'] ?? null;
                $menuItems = [];

                if ($userRole === 'admin') {
                    $menuItems = [
                        'customer/index' => 'Клієнти',
                        'employee/index' => 'Співробітники',
                        'item/index' => 'Товари',
                        'order/index' => 'Замовлення'
                    ];
                } elseif ($userRole === 'employee') {
                    $menuItems = [
                        'customer/index' => 'Клієнти',
                        'item/index' => 'Товари',
                        'order/index' => 'Замовлення'
                    ];
                } elseif ($userRole === 'customer') {
                    $menuItems = [
                        'item/index' => 'Каталог товарів'
                    ];
                }

                foreach ($menuItems as $route => $label): ?>
                <li>
                    <a href="/db-lab/public/index.php?r=<?= $route ?>">
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <main>
			<?= $content ?>
        </main>
    </div>

    <footer>
        &copy; <?= date('Y') ?> ІС "Виделка"
    </footer>
</div>

</body>
</html>
