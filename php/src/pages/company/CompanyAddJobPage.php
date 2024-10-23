<?php
require_once __DIR__ . "/../template/header.php";
require_once __DIR__ . "/../template/navbar.php";
?>

<link href="http://localhost:8000/public/css/pages/companyjobadd.css?v=1.2" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<section id="add-job">
    <div class="form-container container">
        <h3>Create a New Job</h3>

        <?php if (isset($errorMessage)): ?>
            <div class="top-error-message">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form id="addForm">
            <div class="form-group">
                <label for="posisi">Position</label>
                <input type="text" id="posisi" name="posisi" required>
                <p id="position-error" class="error-message">Position cannot be empty.</p>
            </div>

            <div class="form-group">
                <label for="jenis_pekerjaan">Job Type</label>
                <select id="jenis_pekerjaan" name="jenis_pekerjaan" required>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Internship">Internship</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jenis_lokasi">Job Location</label>
                <select id="jenis_lokasi" name="jenis_lokasi" required>
                    <option value="on-site">On-site</option>
                    <option value="hybrid">Hybrid</option>
                    <option value="remote">Remote</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editor">Description</label>
                <div id="editor"></div> <!-- Quill editor -->
                <p id="description-error" class="error-message">Description cannot be empty.</p>
            </div>

            <div class="form-group">
                <label for="attachments">Job Attachments (Multiple images)</label>
                <input type="file" id="attachments" name="attachments[]" accept="image/*" multiple>
            </div>

            <div class="form-group">
                <button company-id="<?= htmlspecialchars($companyDetail['user_id']); ?>" id="save-button"
                    class="btn btn-primary shadow-4" type="submit">Add</button>
            </div>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="http://localhost:8000/public/js/pages/companyjobadd.js"></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>