<?php

?>

<div>
    <div class="page-header">
        <h1>Список співробітників</h1>
        <a href="/db-lab/public/index.php?r=employee/create" class="btn btn-primary">
            + Створити
        </a>
    </div>

	<?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
	<?php endif; ?>

	<?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
	<?php endif; ?>

	<?php if (empty($employees)): ?>
        <div class="empty-state">
            <p class="muted">База даних порожня</p>
            <a href="/db-lab/public/index.php?r=employee/create" class="btn-link">Додати першого співробітника</a>
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
				<?php foreach ($employees as $e): ?>
                    <tr>
                        <td><span class="id-badge">#<?= (int)$e->id ?></span></td>
                        <td class="font-bold"><?= htmlspecialchars($e->firstName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="font-bold"><?= htmlspecialchars($e->lastName, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-muted"><?= htmlspecialchars((string)$e->city, ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="text-muted"><?= htmlspecialchars((string)$e->street, ENT_QUOTES, 'UTF-8') ?></td>
                        <td><span class="badge"><?= htmlspecialchars((string)$e->zipCode, ENT_QUOTES, 'UTF-8') ?></span></td>
                        <td class="actions">
                            <div class="action-group">
                                <a class="btn btn-sm btn-icon" title="Переглянути" href="/db-lab/public/index.php?r=employee/show&id=<?= (int)$e->id ?>">
                                    👁
                                </a>
                                <a class="btn btn-sm btn-icon" title="Редагувати" href="/db-lab/public/index.php?r=employee/edit&id=<?= (int)$e->id ?>">
                                    ✏
                                </a>
                                <form method="post" action="/db-lab/public/index.php?r=employee/destroy&id=<?= (int)$e->id ?>" onsubmit="return confirm('Видалити співробітника #<?= (int)$e->id ?>?');">
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
