<?php require_once __DIR__ . "/../template/header.php"; ?>
<?php require_once __DIR__ . "/../template/navbar.php"; ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/companyprofile.css" />

<section id="company">
    <div class="company-profile-card container shadow-5">
        <div class="company-header">
            <div class="company-info">
                <h1><?= htmlspecialchars($companyDetail['nama']); ?></h1>
                <p class="company-location"><?= htmlspecialchars($companyDetail['lokasi']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($companyDetail['email']); ?></p>
            </div>
        </div>
        
        <div class="company-about">
            <h2>About</h2>
            <p><?= htmlspecialchars($companyDetail['about']); ?></p>
        </div>

        <div class="company-footer">
            <a id="edit-profile" class="btn btn-primary" href="http://localhost:8000/company/profile/edit">Edit Profile</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/../template/footer.php"; ?>
