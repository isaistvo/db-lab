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
            </span>
    </nav>

    <div class="content-wrapper">

        <aside class="sidebar">
            <h3>Меню</h3>
            <ul class="nav-menu">
                <li>
                    <a href="/db-lab/public/index.php?r=customer/index">
                        Клієнти
                    </a>
                </li>
                <li>
                    <a href="/db-lab/public/index.php?r=employee/index">
                        Співробітники
                    </a>
                </li>
                <li>
                    <a href="/db-lab/public/index.php?r=item/index">
                        Товари
                    </a>
                </li>
                <li>
                    <a href="/db-lab/public/index.php?r=order/index">
                        Замовлення
                    </a>
                </li>
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