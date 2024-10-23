<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
?>

<link href="http://localhost:8000/public/css/pages/companyedit.css?v=1.2" rel="stylesheet">

<section id="edit-profile-section">
    <div class="form-container">
        <h3>Edit Profile</h3>

        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form id="editForm">
            <div class="form-group">
                <p><strong>Company Name</strong></p>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($companyDetail['nama']); ?>" required autocomplete="name">
            </div>

            <div class="form-group">
                <p><strong>About</strong></p>
                <textarea type="text" id="about" name="about" required autocomplete="name"><?= htmlspecialchars($companyDetail['about']); ?></textarea>
            </div>

            <div class="form-group">
                <p><strong>Location</strong></p>
                <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($companyDetail['lokasi']); ?>" required autocomplete="name">
            </div>

            <div class="form-group footer">
                <a class="btn btn-secondary" href="http://localhost:8000/company/profile">Cancel</a>
                <button company-id="<?= htmlspecialchars($companyDetail['user_id']); ?>" id="save-button" class="btn btn-primary shadow-4" type="submit" aria-label="edit">Save</button>
            </div>
        </form>
    </div>
</section>

<script src="http://localhost:8000/public/js/pages/companyeditprofile.js"></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>