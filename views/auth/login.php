<?php

?>

<div class="auth-container">
    <div class="auth-form">
        <h1 class="auth-title">Вхід в систему</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php
        $message = $_GET['message'] ?? null;
        if (!empty($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form action="/db-lab/public/index.php?r=auth/authenticate" method="post">
            <div class="form-group">
                <label for="username">Логін:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Увійти</button>
            </div>
        </form>

        <p class="auth-link">
            Немає акаунта? <a href="/db-lab/public/index.php?r=auth/register">Зареєструватися</a>
        </p>
    </div>
</div>
