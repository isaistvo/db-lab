<?php

?>

<div class="form-card">
    <h1>Редагувати замовлення</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (empty($form) || empty($form['id'])): ?>
        <p>Невірний ідентифікатор замовлення.</p>
    <?php else: ?>
        <form method="post" action="/db-lab/public/index.php?r=order/update&id=<?= (int)$form['id'] ?>">
            <div class="form-group">
                <label>Клієнт</label>
                <select name="customer_id" required>
                    <option value="">Оберіть клієнта</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= (int)$customer->id ?>" <?= ($customer->id == ($form['customer_id'] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($customer->firstName . ' ' . $customer->lastName, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Співробітник</label>
                <select name="employee_id" required>
                    <option value="">Оберіть співробітника</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= (int)$employee->id ?>" <?= ($employee->id == ($form['employee_id'] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($employee->firstName . ' ' . $employee->lastName, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Місто доставки</label>
                <input type="text" name="ship_city" value="<?= htmlspecialchars((string)($form['ship_city'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label>Вулиця доставки</label>
                <input type="text" name="ship_street" value="<?= htmlspecialchars((string)($form['ship_street'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label>Поштовий індекс</label>
                <input type="text" name="ship_zip" value="<?= htmlspecialchars((string)($form['ship_zip'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label>Дата доставки</label>
                <input type="date" name="ship_date" value="<?= htmlspecialchars((string)($form['ship_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div style="margin-top: 10px; display:flex; gap:8px;">
                <button type="submit" class="btn-submit">Зберегти</button>
                <a href="/db-lab/public/index.php?r=order/index" class="btn">Скасувати</a>
            </div>
        </form>
    <?php endif; ?>
 </div>

