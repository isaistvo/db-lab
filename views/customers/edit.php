<?php
/**
 * Expected variables:
 * - array $form with keys: id, firstName, lastName, city, street, zipCode
 * - string|null $error
 */
?>
<div class="form-card">
    <h1>Редагувати клієнта</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (empty($form) || empty($form['id'])): ?>
        <p>Невірний ідентифікатор клієнта.</p>
    <?php else: ?>
        <form method="post" action="/db-lab/public/index.php?r=customer/update&id=<?= (int)$form['id'] ?>">
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
            <div style="margin-top: 10px; display:flex; gap:8px;">
                <button type="submit" class="btn-submit">Зберегти</button>
                <a href="/db-lab/public/index.php?r=customer/index" class="btn">Скасувати</a>
            </div>
        </form>
    <?php endif; ?>
</div>
