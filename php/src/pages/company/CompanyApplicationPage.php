<?php require_once __DIR__ . "/../template/header.php"; ?>
<?php require_once __DIR__ . "/../template/navbar.php"; ?>

<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preload" href="http://localhost:8000/public/css/pages/companyapplication.css" as="style">
<link rel="stylesheet" href="http://localhost:8000/public/css/pages/companyapplication.css">

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" media="print" onload="this.media='all'">

<section id="application">
    <div class="application-card container shadow-5">
        <h1>Application</h1>
        
        <div class="applicant-info">
            <h2><?= htmlspecialchars($applicant['user_name']) ?></h2>
            <p>Email: <a href="mailto:<?= htmlspecialchars($applicant['user_email']) ?>" aria-label="Email <?= htmlspecialchars($applicant['user_name']) ?>">
                <?= htmlspecialchars($applicant['user_email']) ?>
            </a></p>
        </div>
        
        <div class="applicant-attachment">
            <?php if (!empty($applicant['cv_path'])): ?>
                <div class="applicant-cv">
                    <label for="cv-view">Applicant's CV attachment</label>
                    <embed id="cv-view" src="<?= 'http://localhost:8000/storage/files/cv/' . htmlspecialchars($applicant['cv_path']) ?>"
                        type="application/pdf" width="600" height="400" aria-label="Applicant's CV" alt="Applicant's CV">
                </div>
            <?php endif; ?>
            
            <?php if (!empty($applicant['video_path'])): ?>
                <div class="applicant-video">
                    <label for="video-view">Applicant's video attachment</label>
                    <video id="video-view" controls class="video-player" aria-label="Applicant's video application">
                        <source src="<?= 'http://localhost:8000/storage/files/videos/' . htmlspecialchars($applicant['video_path']) ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php endif; ?>
        </div>

        <form class="company-respon" id="form-company" method="POST" data-status="<?= htmlspecialchars($applicant['status']) ?>">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="waiting" <?= $applicant['status'] === 'waiting' ? 'selected' : '' ?>>Waiting</option>
                    <option value="accepted" <?= $applicant['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                    <option value="rejected" <?= $applicant['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status-reason">Message for applicant</label>
                <div id="status-reason" aria-label="Message to applicant">
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
