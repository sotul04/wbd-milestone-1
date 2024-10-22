<?php require_once __DIR__ . "/../template/header.php"; ?>
<?php require_once __DIR__ . "/../template/navbar.php"; ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/companyprofile.css" />

<section id="company">
    <div>
        <h1><?= htmlspecialchars($companyDetails['nama']); ?></h1> 
        <p><strong>About:</strong></p> 
        <textarea readonly id="about-editor" value="<?= htmlspecialchars($companyDetails['about']); ?>"></textarea>
            
        <div id="contact-info">
            <h2>Contact Information</h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($companyDetails['email']); ?></p>
            <strong>Address:</strong> 
            <input readonly><?= htmlspecialchars($companyDetails['lokasi']); ?></input>
        </div>

        <div class="footer-profile">
            <button id="edit-profile">Edit</button>
        </div>
    </div>

    <!-- Form untuk memperbarui profil -->
    <!-- <form method="POST">
        <label for="lokasi">Lokasi:</label>
        <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($companyDetails['lokasi']); ?>">

        <label for="about">Tentang Perusahaan:</label>
        <textarea id="about" name="about"><?= htmlspecialchars($companyDetails['about']); ?></textarea>

        <button type="submit">Perbarui Profil</button>
    </form> -->
</section>

<?php require_once __DIR__ . "/../template/footer.php"; ?>
