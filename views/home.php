<div class="welcome-section">
    <h1><?= htmlspecialchars($title ?? 'Панель керування', ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (isset($_SESSION['username'])): ?>
        <p>Ласкаво просимо, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>!</p>

        <?php
        $userRole = $_SESSION['role'] ?? null;
        if ($userRole === 'admin'): ?>
            <p>Як адміністратор, ви маєте повний доступ до всіх розділів системи.</p>
        <?php elseif ($userRole === 'employee'): ?>
            <p>Як співробітник, ви можете керувати клієнтами, товарами та замовленнями.</p>
        <?php elseif ($userRole === 'customer'): ?>
            <p>Як гість, ви можете переглядати каталог товарів.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Будь ласка, увійдіть в систему для доступу до функціоналу.</p>
    <?php endif; ?>
</div>

