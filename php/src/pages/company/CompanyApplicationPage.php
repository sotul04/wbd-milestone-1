<?php require_once __DIR__ . "/../template/header.php"; ?>
<?php require_once __DIR__ . "/../template/navbar.php"; ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/companyapplication.css">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<section id="application">
    <div class="application-card container shadow-5">
        <h1>Application</h1>
        <div class="applicant-info">
            <h2><?= htmlspecialchars($applicant['user_name']) ?></h2>
            <p>email: <?= htmlspecialchars($applicant['user_email']) ?></p>
        </div>
        <div class="applicant-attachment">
            <?php if (!empty($applicant['cv_path'])): ?>
                <div class="applicant-cv">
                    <label>Applicant's CV attachment</label>
                    <embed src="<?= 'http://localhost:8000/storage/files/cv/' . htmlspecialchars($applicant['cv_path']) ?>"
                        type="application/pdf" width="600" height="400" aria-label="Your CV" alt="Your CV">
                </div>
            <? endif; ?>
            <?php if (!empty($applicant['video_path'])): ?>
                <div class="applicant-video">
                    <label>Applicant's video attachment</label>
                    <video controls class="video-player" aria-label="Video application">
                        <source
                            src="<?= 'http://localhost:8000/storage/files/videos/' . htmlspecialchars($applicant['video_path']) ?>"
                            type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <? endif; ?>
        </div>
        <form class="company-respon" id="form-company">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="waiting" <?= $applicant['status'] === 'waiting' ? 'selected' : '' ?>>Waiting</option>
                    <option value="accepted" <?= $applicant['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                    <option value="rejected" <?= $applicant['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="form-group">
                <label>Message for applicant</label>
                <div id="status-reason">
                    <?= isset($applicant['status_reason']) ? htmlspecialchars_decode($applicant['status_reason']) : '' ?>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="http://localhost:8000/public/js/pages/companyapplication.js"></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>