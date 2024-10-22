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

        <form id="editForm" action="" method="POST">
            <div class="form-group">
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($companyDetail['nama']); ?>" required autocomplete="name">
            </div>

            <div class="form-group">
                <input type="text" id="about" name="about" value="<?= htmlspecialchars($companyDetail['about']); ?>" required autocomplete="name">
            </div>

            <div class="form-group">
                <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($companyDetail['lokasi']); ?>" required autocomplete="name">
            </div>

            <div class="form-group">
                <button class="btn btn-primary shadow-4" type="submit" aria-label="edit">Save</button>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . "/../template/footer.php"; ?>