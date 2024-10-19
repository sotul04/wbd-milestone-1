<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
?>

<!-- Link CSS untuk halaman login -->
<link href="http://localhost:8000/public/css/pages/login.css?v=1.2" rel="stylesheet">

<section id="login-section">
    <div class="form-container">
        <h3>Login</h3>
        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" action="http://localhost:8000/auth/login" method="POST">
            <div class="form-group">
                <label for="email">
                    <img src="http://localhost:8000/public/assets/icons/Email.ico" alt="Email Icon">
                </label>
                <input type="email" id="email" name="email" placeholder="Email" required autocomplete="off"
                    value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">
                    <img src="http://localhost:8000/public/assets/icons/Password.ico" alt="Password Icon">
                </label>
                <input type="password" id="password" name="password" placeholder="Password" required
                    autocomplete="current-password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary shadow-4" type="submit" aria-label="Login">LOGIN</button>
            </div>
        </form>

        <!-- Link ke halaman registrasi -->
        <p>Don't have an account? <a class="link" href="http://localhost:8000/user/register">Register here</a>.</p>
    </div>
</section>

<script src="http://localhost:8000/public/js/pages/login.js?v=1.2" defer></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>