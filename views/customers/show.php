<?php

?>
<div class="form-card">
    <h1>Перегляд клієнта</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($customer)): ?>
        <div class="form-group"><label>ID</label><div><?= (int)$customer->id ?></div></div>
        <div class="form-group"><label>Ім'я</label><div><?= htmlspecialchars($customer->firstName, ENT_QUOTES, 'UTF-8') ?></div></div>
        <div class="form-group"><label>Прізвище</label><div><?= htmlspecialchars($customer->lastName, ENT_QUOTES, 'UTF-8') ?></div></div>
        <div class="form-group"><label>Місто</label><div><?= htmlspecialchars((string)$customer->city, ENT_QUOTES, 'UTF-8') ?></div></div>
        <div class="form-group"><label>Вулиця</label><div><?= htmlspecialchars((string)$customer->street, ENT_QUOTES, 'UTF-8') ?></div></div>
        <div class="form-group"><label>Поштовий індекс</label><div><?= htmlspecialchars((string)$customer->zipCode, ENT_QUOTES, 'UTF-8') ?></div></div>

        <div style="margin-top: 12px; display: flex; gap: 8px;">
            <a href="/db-lab/public/index.php?r=customer/edit&id=<?= (int)$customer->id ?>" class="btn">Редагувати</a>
            <a href="/db-lab/public/index.php?r=customer/index" class="btn">До списку</a>
        </div>
    <?php endif; ?>
</div>

