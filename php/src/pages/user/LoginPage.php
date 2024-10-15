<?php 
require_once __DIR__ . "/../template/header.php"; 
require_once __DIR__ . "/../template/navbar.php"; 
?>

<section id="title">
    <h2>LOGIN</h2>

    <?php if (isset($errorMessage)): ?>
        <div class="error-message">
            <?= htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form action="http://localhost:8000/auth/login" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>

    <p>Don't have an account? <a class="link" href="http://localhost:8000/user/register">Register here</a>.</p>
</section>

<?php require_once __DIR__ . "/../template/footer.php"; ?>
