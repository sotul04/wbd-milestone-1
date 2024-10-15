<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<style>
    /* style for home */
</style>

<section id="title">
    <h2>HOME PAGE</h2>
    <a href="http://localhost:8000/user/login">Login</a>
    <a href="http://localhost:8000/user/register">Register</a>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="http://localhost:8000/auth/logout" method="POST">
            <button type="submit">Logout</button>
        </form>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . "/../template/footer.php" ?>