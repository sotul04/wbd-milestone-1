<?php require_once __DIR__ . "/../template/header.php"; ?>
<?php require_once __DIR__ . "/../template/navbar.php"; ?>
<?php require_once __DIR__ . "/../../models/CompanyDetailModel.php"; ?>

<?php 
$companyModel = new CompanyDetailModel();
$userId = $_SESSION['user_id']; 

$companyDetails = $companyModel->getCompanyByUserId($userId);

if (!$companyDetails) {
    echo "<p>Perusahaan tidak ditemukan.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lokasi = $_POST['lokasi'];
    $about = $_POST['about'];
    $companyModel->updateCompanyDetail($userId, $lokasi, $about); 
    $companyDetails = $companyModel->getCompanyByUserId($userId);
}
?>

<link rel="stylesheet" href="<?php echo BASE_URL ?>/public/css/pages/companypages.css" />

<section id="company">
    <h1><?= htmlspecialchars($companyDetails['nama']); ?></h1> 
    <p><strong>About:</strong> <?= htmlspecialchars($companyDetails['about']); ?></p>
        
    <div id="contact-info">
        <h2>Informasi Kontak</h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($companyDetails['email']); ?></p>
        <p><strong>Alamat:</strong> <?= htmlspecialchars($companyDetails['lokasi']); ?></p>
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
