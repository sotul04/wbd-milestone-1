<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
// the style register.css added via javascript
?>

<section id="register-section">
    <div class="form-container">
        <h3>Register</h3>

        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm">
            <div class="form-group">
                <label for="userType">Select your role</label>
                <select id="userType" name="userType">
                    <option value="jobseeker">Jobseeker</option>
                    <option value="company">Company</option>
                </select>
            </div>

            <div class="form-group">
                <img src="http://localhost:8000/public/assets/icons/User.ico" alt="User Icon">
                <input type="text" id="name" name="name" placeholder="Full Name" required autocomplete="off">
            </div>

            <div class="form-group">
                <img src="http://localhost:8000/public/assets/icons/Email.ico" alt="Email Icon">
                <input type="email" id="email" name="email" placeholder="Email" required autocomplete="off">
            </div>
            <div class="form-group">
                <img src="http://localhost:8000/public/assets/icons/Password.ico" alt="Password Icon">
                <input type="password" id="password" name="password" placeholder="Password" required autocomplete="off">
            </div>
            <div class="form-group">
                <img src="http://localhost:8000/public/assets/icons/Password.ico" alt="Password Icon">
                <input type="password" id="confirm" name="confirm" placeholder="Confirm Password" required
                    autocomplete="off">
            </div>
            <div class="form-group">
                <button class="btn btn-primary shadow-4" type="submit">Register</button>
            </div>
        </form>
        <p>Already have an account? <a class="link" href="http://localhost:8000/user/login">Login here</a>.</p>
    </div>
</section>

<script src="http://localhost:8000/public/js/pages/register.js"></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>