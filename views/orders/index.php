<?php

?>

<div>
    <div class="page-header">
        <h1>Список замовлень</h1>
        <a href="/db-lab/public/index.php?r=order/create" class="btn btn-primary">+ Створити</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p class="muted">База даних порожня</p>
            <a href="/db-lab/public/index.php?r=order/create" class="btn-link">Додати перше замовлення</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>ID клієнта</th>
                    <th>ID співробітника</th>
                    <th>Місто доставки</th>
                    <th>Вулиця доставки</th>
                    <th>Поштовий індекс</th>
                    <th>Дата доставки</th>
                    <th class="text-right">Дії</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><span class="id-badge">#<?= (int)$o->id ?></span></td>
                        <td><?= (int)$o->customerId ?></td>
                        <td><?= (int)$o->employeeId ?></td>
                        <td><?= htmlspecialchars((string)($o->shipCity ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string)($o->shipStreet ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string)($o->shipZip ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string)($o->shipDate?->format('Y-m-d') ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="actions">
                            <div class="action-group">
                                <a class="btn btn-sm btn-icon" title="Переглянути" href="/db-lab/public/index.php?r=order/show&id=<?= (int)$o->id ?>">👁</a>
                                <a class="btn btn-sm btn-icon" title="Редагувати" href="/db-lab/public/index.php?r=order/edit&id=<?= (int)$o->id ?>">✏</a>
                                <form method="post" action="/db-lab/public/index.php?r=order/destroy&id=<?= (int)$o->id ?>" onsubmit="return confirm('Видалити замовлення #<?= (int)$o->id ?>?');">
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

