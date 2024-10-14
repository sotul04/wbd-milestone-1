<?php 
require_once __DIR__ . "/../template/header.php"; 
require_once __DIR__ . "/../template/navbar.php"; 
?>

<style>
    /* style for register form */
    .form-container {
        max-width: 400px;
        margin: auto;
        padding: 2rem;
        border: 1px solid #ccc;
        border-radius: 10px;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
    }
    .form-group input {
        width: 100%;
        padding: 0.5rem;
        font-size: 1rem;
    }
    .form-group select {
        width: 100%;
        padding: 0.5rem;
        font-size: 1rem;
    }
    .form-group button {
        padding: 0.5rem 1rem;
        font-size: 1rem;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }
    .form-group button:hover {
        background-color: #0056b3;
    }
</style>

<section id="title">
    <h2>REGISTER</h2>

    <div class="form-container">
        <form action="http://localhost:8000/auth/register" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="jobseeker">Job Seeker</option>
                    <option value="company">Company</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
    </div>

    <p>Already have an account? <a href="http://localhost:8000/user/login">Login here</a>.</p>
</section>

<?php require_once __DIR__ . "/../template/footer.php"; ?>
