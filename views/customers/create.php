<div class="form-card">
    <h1>Створити клієнта</h1>

	<?php if (!empty($message)): ?>
        <div class="alert alert-success">
			<?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
	<?php endif; ?>

	<?php if (!empty($error)): ?>
        <div class="alert alert-error">
			<?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
	<?php endif; ?>

    <form method="post" action="/db-lab/public/index.php?r=customer/store">

        <div class="form-group">
            <label for="firstName">Ім'я <span style="color:red">*</span></label>
            <input type="text" id="firstName" name="firstName" required placeholder="Введіть ім'я" value="<?= htmlspecialchars($old['firstName'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
            <label for="lastName">Прізвище <span style="color:red">*</span></label>
            <input type="text" id="lastName" name="lastName" required placeholder="Введіть прізвище" value="<?= htmlspecialchars($old['lastName'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
            <label for="city">Місто</label>
            <input type="text" id="city" name="city" placeholder="Наприклад: Київ" value="<?= htmlspecialchars($old['city'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
            <label for="street">Вулиця</label>
            <input type="text" id="street" name="street" placeholder="Вулиця та номер будинку" value="<?= htmlspecialchars($old['street'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
            <label for="zipCode">Поштовий індекс</label>
            <input type="text" id="zipCode" name="zipCode" placeholder="00000" value="<?= htmlspecialchars($old['zipCode'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Створити запис</button>
            <a href="/db-lab/public/index.php?r=customer/index" class="btn btn-secondary">Скасувати</a>
        </div>

    </form>
</div>