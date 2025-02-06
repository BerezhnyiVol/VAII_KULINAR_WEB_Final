<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prihlásenie</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/auth.css"> <!-- Pripojenie štýlov -->
</head>
<body>
<div class="container">
    <h2>Prihlásenie</h2>

    <form method="POST" action="/VAII_KULINAR_WEB/public/index.php/login/attempt">
        <label for="username">Meno alebo Email:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Prihlásiť sa</button>
    </form>

    <a href="/VAII_KULINAR_WEB/public/index.php/register">Registrácia</a>
</div>
</body>
</html>
