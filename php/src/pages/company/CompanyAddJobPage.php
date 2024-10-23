<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
?>

<!-- Preconnect to CDN and preload local CSS -->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preload" href="http://localhost:8000/public/css/pages/companyjobadd.css?v=1.2" as="style">
<link href="http://localhost:8000/public/css/pages/companyjobadd.css?v=1.2" rel="stylesheet">

<!-- Load Quill CSS asynchronously -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" media="print"
    onload="this.media='all'">

<section id="add-job">
    <div class="form-container container">
        <h3>Create a New Job</h3>

        <!-- Error Message -->
        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message" role="alert" aria-live="polite">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Job Creation Form -->
        <form id="addForm" method="POST">
            <div class="form-group">
                <label for="posisi">Position</label>
                <input type="text" id="posisi" name="posisi" required aria-required="true"
                    aria-describedby="position-error">
                <p id="position-error" class="error-message" aria-live="assertive">Position cannot be empty.</p>
            </div>

            <div class="form-group">
                <label for="jenis_pekerjaan">Job Type</label>
                <select id="jenis_pekerjaan" name="jenis_pekerjaan" required aria-required="true">
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Internship">Internship</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jenis_lokasi">Job Location</label>
                <select id="jenis_lokasi" name="jenis_lokasi" required aria-required="true">
                    <option value="on-site">On-site</option>
                    <option value="hybrid">Hybrid</option>
                    <option value="remote">Remote</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editor">Description</label>
                <div id="editor" aria-label="Job description editor"></div> <!-- Quill editor -->
                <p id="description-error" class="error-message" aria-live="assertive">Description cannot be empty.</p>
            </div>

            <div class="form-group">
                <label for="attachments">Job Attachments (Max 20MB per image)</label>
                <input type="file" id="attachments" name="attachments[]" accept="image/*" multiple
                    aria-describedby="attachments-info">
                <p id="attachments-info">Accepts multiple images. Max size 20MB each.</p>
            </div>

            <div class="form-group">
                <button company-id="<?= htmlspecialchars($companyDetail['user_id']); ?>" id="save-button"
                    class="btn btn-primary shadow-4" type="submit">Add</button>
            </div>
        </form>
    </div>
</section>

<!-- Load scripts asynchronously -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js" async></script>
<script src="http://localhost:8000/public/js/pages/companyjobadd.js" async></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>