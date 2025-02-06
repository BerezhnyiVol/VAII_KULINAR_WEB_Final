<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrácia</title>
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/css/auth.css">
</head>
<body>
<div class="container">
    <h2>Registrácia</h2>
    <form action="/VAII_KULINAR_WEB/public/index.php/register/attempt" method="POST">
        <input type="text" name="name" placeholder="Meno" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Heslo" required>
        <button type="submit">Zaregistrovať sa</button>
    </form>
    <a href="/VAII_KULINAR_WEB/public/index.php/login">Už máte účet? Prihlásiť sa</a>
</div>
</body>
</html>
