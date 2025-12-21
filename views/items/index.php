<?php
/** @var array $items */
?>

<div>
    <div class="page-header">
        <h1>Список товарів</h1>
        <a href="/db-lab/public/index.php?r=item/create" class="btn btn-primary">
            + Створити
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="empty-state">
            <p class="muted">База даних порожня</p>
            <a href="/db-lab/public/index.php?r=item/create" class="btn-link">Додати перший товар</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Назва</th>
                    <th>Ціна</th>
                    <th>Кількість</th>
                    <th>Гарантія (міс.)</th>
                    <th class="text-right">Дії</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $i): ?>
                    <tr>
                        <td><span class="id-badge">#<?= (int)$i->id ?></span></td>
                        <td class="font-bold"><?= htmlspecialchars($i->name, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format((float)$i->price, 2, '.', '') ?></td>
                        <td class="font-bold"><?= (int)$i->quantity ?></td>
                        <td><span class="badge"><?= (int)$i->guarantee ?></span></td>
                        <td class="actions">
                            <div class="action-group">
                                <a class="btn btn-sm btn-icon" title="Переглянути" href="/db-lab/public/index.php?r=item/show&id=<?= (int)$i->id ?>">
                                    👁
                                </a>
                                <a class="btn btn-sm btn-icon" title="Редагувати" href="/db-lab/public/index.php?r=item/edit&id=<?= (int)$i->id ?>">
                                    ✏
                                </a>
                                <form method="post" action="/db-lab/public/index.php?r=item/destroy&id=<?= (int)$i->id ?>" onsubmit="return confirm('Видалити товар #<?= (int)$i->id ?>?');">
                                    <button type="submit" class="btn btn-sm btn-danger btn-icon" title="Видалити">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
