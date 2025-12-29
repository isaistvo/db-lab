<?php

?>

<div class="auth-container">
    <div class="auth-form">
        <h1 class="auth-title">Реєстрація</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="/db-lab/public/index.php?r=auth/store" method="post">
            <div class="form-group">
                <label for="username">Логін <span class="required">*</span></label>
                <input type="text" id="username" name="username" required placeholder="Введіть логін">
            </div>

            <div class="form-group">
                <label for="password">Пароль <span class="required">*</span></label>
                <input type="password" id="password" name="password" required placeholder="Мінімум 6 символів">
            </div>

            <div class="form-group">
                <label for="role">Роль</label>
                <select id="role" name="role">
                    <option value="employee">Співробітник</option>
                    <option value="customer">Клієнт</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Зареєструватися</button>
            </div>
        </form>

        <p class="auth-link">
            Вже маєте акаунт? <a href="/db-lab/public/index.php?r=auth/login">Увійти</a>
        </p>
    </div>
</div>
