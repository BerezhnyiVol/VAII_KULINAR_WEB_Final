<h1><?= htmlspecialchars($recipe['name']) ?></h1>

<!-- Zobrazenie fotografie, ak existuje -->
<?php if (!empty($recipe['image'])): ?>
    <img src="<?= htmlspecialchars($recipe['image']) ?>" alt="Fotografia receptu" class="recipe-image">
<?php endif; ?>

<p><?= htmlspecialchars($recipe['description']) ?></p>
<link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/info.css">

<h3>Ingrediencie:</h3>
<?php if (!empty($ingredients)): ?>
    <ul>
        <?php foreach ($ingredients as $ingredient): ?>
            <li><?= htmlspecialchars($ingredient['name']) ?>: <?= htmlspecialchars($ingredient['amount']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Žiadne ingrediencie nie sú k dispozícii.</p>
<?php endif; ?>

<a href="/VAII_KULINAR_WEB/public/index.php/recipes">🔙 Späť na zoznam</a>
