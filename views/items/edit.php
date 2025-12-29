<?php

?>

<div class="form-card">
    <h1>Редагувати товар</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (empty($form) || empty($form['id'])): ?>
        <p>Невірний ідентифікатор товару.</p>
    <?php else: ?>
        <form method="post" action="/db-lab/public/index.php?r=item/update&id=<?= (int)$form['id'] ?>">
            <div class="form-group">
                <label>Назва</label>
                <input type="text" name="name" value="<?= htmlspecialchars((string)($form['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group">
                <label>Ціна</label>
                <input type="number" step="0.01" min="0" name="price" value="<?= htmlspecialchars((string)($form['price'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group">
                <label>Кількість</label>
                <input type="number" min="0" name="quantity" value="<?= htmlspecialchars((string)($form['quantity'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group">
                <label>Гарантія (місяців)</label>
                <input type="number" min="0" name="guarantee" value="<?= htmlspecialchars((string)($form['guarantee'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div style="margin-top: 10px; display:flex; gap:8px;">
                <button type="submit" class="btn-submit">Зберегти</button>
                <a href="/db-lab/public/index.php?r=item/index" class="btn">Скасувати</a>
            </div>
        </form>
    <?php endif; ?>
</div>

