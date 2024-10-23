<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
?>

<link rel="preload" href="/public/css/pages/companyjobedit.css" as="style">
<link href="/public/css/pages/companyjobedit.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<section id="edit-job" role="form" aria-labelledby="edit-job-title">
    <div class="form-container container">
        <h3 id="edit-job-title">Edit Job</h3>

        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message" role="alert" aria-live="assertive">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form id="editJobForm" enctype="multipart/form-data" method="POST" action="/company/update-job">
            <div class="form-group">
                <label for="position"><strong>Position</strong></label>
                <input type="text" id="position" name="position" value="<?= htmlspecialchars($jobDetail['posisi']); ?>"
                    required autocomplete="off" aria-required="true">
                <p id="position-error" class="error-message" aria-live="polite">Position cannot be empty.</p>
            </div>

            <div class="form-group">
                <label for="jenisPekerjaan"><strong>Job Type</strong></label>
                <select id="jenisPekerjaan" name="jenisPekerjaan" required>
                    <option value="Full-time" <?= $jobDetail['jenis_pekerjaan'] === 'Full-time' ? 'selected' : ''; ?>>Full-time</option>
                    <option value="Part-time" <?= $jobDetail['jenis_pekerjaan'] === 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
                    <option value="Internship" <?= $jobDetail['jenis_pekerjaan'] === 'Internship' ? 'selected' : ''; ?>>Internship</option>
                </select>
            </div>

            <div class="form-group">
                <label for="lokasi"><strong>Job Location</strong></label>
                <select id="lokasi" name="lokasi" required>
                    <option value="on-site" <?= $jobDetail['jenis_lokasi'] === 'on-site' ? 'selected' : ''; ?>>On-site</option>
                    <option value="hybrid" <?= $jobDetail['jenis_lokasi'] === 'hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                    <option value="remote" <?= $jobDetail['jenis_lokasi'] === 'remote' ? 'selected' : ''; ?>>Remote</option>
                </select>
            </div>

            <div class="form-group">
                <label><strong>Description</strong></label>
                <div id="editor" aria-label="Job Description"><?= htmlspecialchars_decode($jobDetail['deskripsi']); ?></div>
                <p id="description-error" class="error-message" aria-live="polite">Description cannot be empty.</p>
            </div>

            <div class="form-group">
                <label for="attachments"><strong>Job Attachments (Max 20MB per image)</strong></label>
                <input type="file" id="attachments" name="attachments[]" accept="image/*" multiple>
            </div>

            <div class="form-group footer">
                <a class="btn btn-secondary" href="/company/job/<?= $jobDetail['lowongan_id']?>">Cancel</a>
                <button job-id="<?= htmlspecialchars($jobDetail['lowongan_id']); ?>" id="save-button"
                    class="btn btn-primary shadow-4" type="submit" aria-label="Save Job Changes">Save</button>
            </div>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js" async></script>
<script src="/public/js/pages/companyeditjob.js" defer></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>
