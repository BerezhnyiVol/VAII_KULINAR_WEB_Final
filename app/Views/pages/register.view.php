<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/css/auth.css">
</head>
<body>
<div class="container">
    <h2>Регистрация</h2>
    <form action="/VAII_KULINAR_WEB/public/index.php/register/attempt" method="POST">
        <input type="text" name="name" placeholder="Имя" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Зарегистрироваться</button>
    </form>
    <a href="/VAII_KULINAR_WEB/public/index.php/login">Уже есть аккаунт? Войти</a>
</div>
</body>
</html>
