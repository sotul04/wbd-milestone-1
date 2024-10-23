<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
?>

<link href="http://localhost:8000/public/css/pages/companyjobedit.css" rel="stylesheet">

<section id="edit-profile-section">
    <div class="form-container">
        <h3>Edit Job</h3>

        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form id="editJobForm">
            <div class="form-group">
                <p><strong>Position</strong></p>
                <input type="text" id="position" name="position" value="<?= htmlspecialchars($jobDetail['posisi']); ?>" required autocomplete="name">
            </div>

            <div class="form-group">
                <p><strong>Description</strong></p>
                <textarea type="text" id="description" name="description" required autocomplete="name"><?= htmlspecialchars($jobDetail['deskripsi']); ?></textarea>
            </div>

            <div class="form-group">
                <p><strong>Job Category</strong></p>
                <select id="jenisPekerjaan" name="jenisPekerjaan" required>
                    <option value="Full-time" <?= $jobDetail['jenis_pekerjaan'] == 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
                    <option value="Part-time" <?= $jobDetail['jenis_pekerjaan'] == 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
                    <option value="Internship" <?= $jobDetail['jenis_pekerjaan'] == 'Internship' ? 'selected' : ''; ?>>Internship</option>
                </select>
            </div>

            <div class="form-group">
                <p><strong>Location Category</strong></p>
                <select id="lokasi" name="lokasi" required>
                    <option value="On-site" <?= $jobDetail['jenis_lokasi'] == 'On-site' ? 'selected' : ''; ?>>On-site</option>
                    <option value="Hybrid" <?= $jobDetail['jenis_lokasi'] == 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                    <option value="Remote" <?= $jobDetail['jenis_lokasi'] == 'Remote' ? 'selected' : ''; ?>>Remote</option>
                </select>
            </div>

            <div class="form-group">
                <button job-id="<?= htmlspecialchars($jobDetail['lowongan_id']); ?>" id="save-button" class="btn btn-primary shadow-4" type="submit" aria-label="edit">Save</button>
            </div>
        </form>
    </div>
</section>

<script src="http://localhost:8000/public/js/pages/companyeditjob.js"></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>