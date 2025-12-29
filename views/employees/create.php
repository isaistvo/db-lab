<?php

?>

<div class="form-card">
    <h1>Створити співробітника</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="/db-lab/public/index.php?r=employee/store">
        <div class="form-group">
            <label>Ім'я</label>
            <input type="text" name="firstName" value="<?= htmlspecialchars((string)($form['firstName'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label>Прізвище</label>
            <input type="text" name="lastName" value="<?= htmlspecialchars((string)($form['lastName'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label>Місто</label>
            <input type="text" name="city" value="<?= htmlspecialchars((string)($form['city'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label>Вулиця</label>
            <input type="text" name="street" value="<?= htmlspecialchars((string)($form['street'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label>Поштовий індекс</label>
            <input type="text" name="zipCode" value="<?= htmlspecialchars((string)($form['zipCode'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <button type="submit" class="btn-submit">Створити</button>
        <a href="/db-lab/public/index.php?r=employee/index" class="btn" style="margin-left:8px;">До списку</a>
    </form>
</div>

