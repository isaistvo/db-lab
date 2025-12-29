<?php

?>

<div class="form-card">
    <h1>Перегляд товару</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($item)): ?>
        <div class="details">
            <p><strong>ID:</strong> <span class="id-badge">#<?= (int)$item->id ?></span></p>
            <p><strong>Назва:</strong> <?= htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong>Ціна:</strong> <?= number_format((float)$item->price, 2, '.', '') ?></p>
            <p><strong>Кількість:</strong> <?= (int)$item->quantity ?></p>
            <p><strong>Гарантія (місяців):</strong> <span class="badge"><?= (int)$item->guarantee ?></span></p>
        </div>

        <div style="margin-top: 16px; display:flex; gap:8px;">
            <a href="/db-lab/public/index.php?r=item/edit&id=<?= (int)$item->id ?>" class="btn">Редагувати</a>
            <a href="/db-lab/public/index.php?r=item/index" class="btn">Назад до списку</a>
        </div>
    <?php else: ?>
        <p class="muted">Товар не знайдено.</p>
        <a href="/db-lab/public/index.php?r=item/index" class="btn">Повернутися</a>
    <?php endif; ?>
</div>

