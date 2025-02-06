<header class="header">
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/header.css">

    <div class="logo">
        <h1>🍽️ Kulinársky Web</h1>
    </div>

    <?php if (isset($_SESSION['user_id'])) : ?>
        <a href="/VAII_KULINAR_WEB/public/index.php/logout" class="logout-button">🚪Odhlásiť sa</a>
    <?php endif; ?>

    <nav class="nav">
        <a href="/VAII_KULINAR_WEB/public/index.php/home" class="button-nav">🏠 Domov</a>
        <a href="/VAII_KULINAR_WEB/public/index.php/about" class="button-nav">O nás</a>
        <a href="/VAII_KULINAR_WEB/public/index.php/recipes" class="button-nav">Recepty</a>
    </nav>

    <?php if ($_SERVER['REQUEST_URI'] === '/VAII_KULINAR_WEB/public/index.php/recipes') : ?>
        <a href="/VAII_KULINAR_WEB/public/index.php/recipe/create" class="button-nav add-recipe-button">➕ Pridať recept</a>
    <?php endif; ?>

</header>
