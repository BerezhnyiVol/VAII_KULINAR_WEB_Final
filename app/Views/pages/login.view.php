<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/auth.css"> <!-- Подключаем стили -->
</head>
<body>
<div class="container">
    <h2>Вход</h2>

    <form method="POST" action="/VAII_KULINAR_WEB/public/index.php/login/attempt">
        <label for="username">Имя или Email:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Войти</button>
    </form>

    <a href="/VAII_KULINAR_WEB/public/index.php/register">Регистрация</a>
</div>
</body>
</html>
