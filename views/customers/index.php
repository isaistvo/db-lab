<?php

?>

<div>
    <div class="page-header">
        <h1>Список клієнтів</h1>
        <a href="/db-lab/public/index.php?r=customer/create" class="btn btn-primary">
            + Створити
        </a>
    </div>

	<?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
	<?php endif; ?>

	<?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
	<?php endif; ?>

	<?php if (empty($customers)): ?>
        <div class="empty-state">
            <p class="muted">База даних порожня</p>
            <a href="/db-lab/public/index.php?r=customer/create" class="btn-link">Додати перший запис</a>
        </div>
	<?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Ім'я</th>
                    <th>Прізвище</th>
                    <th>Місто</th>
                    <th>Вулиця</th>
                    <th>Індекс</th>
                    <th class="text-right">Дії</th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ($customers as $c): ?>
                    <tr>
                        <td><span class="id-badge">#<?= (int)$c->id ?></span></td>
                        <td class="font-bold"><?= htmlspecialchars($c->firstName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="font-bold"><?= htmlspecialchars($c->lastName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-muted"><?= htmlspecialchars((string)$c->city, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-muted"><?= htmlspecialchars((string)$c->street, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><span class="badge"><?= htmlspecialchars((string)$c->zipCode, ENT_QUOTES, 'UTF-8') ?></span></td>
                        <td class="actions">
                            <div class="action-group">
                                <a class="btn btn-sm btn-icon" title="Переглянути" href="/db-lab/public/index.php?r=customer/show&id=<?= (int)$c->id ?>">
                                    👁
                                </a>
                                <a class="btn btn-sm btn-icon" title="Редагувати" href="/db-lab/public/index.php?r=customer/edit&id=<?= (int)$c->id ?>">
                                    ✏
                                </a>
                                <form method="post" action="/db-lab/public/index.php?r=customer/destroy&id=<?= (int)$c->id ?>" onsubmit="return confirm('Видалити клієнта #<?= (int)$c->id ?>?');">
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
