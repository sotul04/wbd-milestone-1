<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
?>

<!-- Link CSS untuk halaman register -->
<link href="http://localhost:8000/public/css/pages/register.css?v=1.2" rel="stylesheet">

<section id="register-section">
    <div class="form-container">
        <h3>Register</h3>

        <!-- Pesan error jika ada -->
        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form id="registerForm" action="http://localhost:8000/auth/register" method="POST">
            <div class="form-group">
                <label for="userType">Select your role</label>
                <select id="userType" name="userType" required>
                    <option value="jobseeker">Jobseeker</option>
                    <option value="company">Company</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">
                    <img src="http://localhost:8000/public/assets/icons/User.ico" alt="User Icon">
                </label>
                <input type="text" id="name" name="name" placeholder="Full Name" required autocomplete="name">
            </div>

            <div class="form-group">
                <label for="email">
                    <img src="http://localhost:8000/public/assets/icons/Email.ico" alt="Email Icon">
                </label>
                <input type="email" id="email" name="email" placeholder="Email" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">
                    <img src="http://localhost:8000/public/assets/icons/Password.ico" alt="Password Icon">
                </label>
                <input type="password" id="password" name="password" placeholder="Password" required
                    autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="confirm">
                    <img src="http://localhost:8000/public/assets/icons/Password.ico" alt="Confirm Password Icon">
                </label>
                <input type="password" id="confirm" name="confirm" placeholder="Confirm Password" required
                    autocomplete="new-password">
            </div>

            <div class="form-group">
                <button class="btn btn-primary shadow-4" type="submit" aria-label="Register">Register</button>
            </div>
        </form>

        <!-- Link ke halaman login -->
        <p>Already have an account? <a class="link" href="http://localhost:8000/user/login">Login here</a>.</p>
    </div>
</section>

<!-- Optimasi script dengan defer -->
<script src="http://localhost:8000/public/js/pages/register.js?" defer></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>