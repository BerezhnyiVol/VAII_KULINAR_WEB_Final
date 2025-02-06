<footer class="footer">
    <link rel="stylesheet" href="/VAII_KULINAR_WEB/public/assets/css/footer.css">
    <p>&copy; <?php echo date("Y"); ?> VAII_KULINAR_WEB.
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
        Všetky práva vyhradené. --ADMIN--</p>
        <?php endif; ?>


    </nav>
</footer>
