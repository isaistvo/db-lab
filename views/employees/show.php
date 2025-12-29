<?php

?>

<div class="form-card">
    <h1>Перегляд співробітника</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($employee)): ?>
        <div class="details">
            <p><strong>ID:</strong> <span class="id-badge">#<?= (int)$employee->id ?></span></p>
            <p><strong>Ім'я:</strong> <?= htmlspecialchars($employee->firstName, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Прізвище:</strong> <?= htmlspecialchars($employee->lastName, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Місто:</strong> <?= htmlspecialchars((string)$employee->city, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Вулиця:</strong> <?= htmlspecialchars((string)$employee->street, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Поштовий індекс:</strong> <span class="badge"><?= htmlspecialchars((string)$employee->zipCode, ENT_QUOTES, 'UTF-8') ?></span></p>
        </div>

        <div style="margin-top: 16px; display:flex; gap:8px;">
            <a href="/db-lab/public/index.php?r=employee/edit&id=<?= (int)$employee->id ?>" class="btn">Редагувати</a>
            <a href="/db-lab/public/index.php?r=employee/index" class="btn">Назад до списку</a>
        </div>
    <?php else: ?>
        <p class="muted">Співробітника не знайдено.</p>
        <a href="/db-lab/public/index.php?r=employee/index" class="btn">Повернутися</a>
    <?php endif; ?>
</div>

