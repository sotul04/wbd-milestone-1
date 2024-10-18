<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/jobdetail.css">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<section id="job-detail">
    <div class="container job-container shadow-4">
        <h1 class="job-title"><?= htmlspecialchars($jobDetail['posisi']) ?></h1>
        <div class="company-info">
            <h2 class="company-name"><?= htmlspecialchars($jobDetail['company_name']) ?></h2>
            <p class="job-location"><strong>Location: </strong><?= htmlspecialchars($jobDetail['jenis_lokasi']) ?></p>
        </div>
        <div class="job-description">
            <label for="job-description-editor">Description:</label>
            <div id="job-description-editor">
                <?= htmlspecialchars($jobDetail['deskripsi']) ?>
            </div>
        </div>
        <div class="job-type">
            <p><strong>Type:</strong> <?= htmlspecialchars($jobDetail['jenis_pekerjaan']) ?></p>
        </div>
        <div class="job-posted-date">
            <p>Posted on: <?= htmlspecialchars(date('F j, Y', strtotime($jobDetail['created_at']))) ?></p>
        </div>
        <?
        if ($role === 'jobseeker' && empty($infoApplication)) {
            ?>
            <div class="apply-button">
                <a href="http://localhost:8000/job/<?= htmlspecialchars($jobDetail['lowongan_id']) ?>/application"
                    class="btn">Apply Now</a>
            </div>
        <?
        }
        ?>
        <div class="photos">
            <?php if (isset($attachments)): ?>
                <?php foreach ($attachments as $photo): ?>
                    <img src="<?= 'http://localhost:8000/public/files/attachments/' . $photo['file_path'] ?>" class="shadow-5">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Application Section -->
    <?php if (isset($infoApplication) && $infoApplication !== false): ?>
        <div class="container job-container application shadow-4">
            <h2 class="application-title">Your application</h2>
            <p class="application-status <?= $infoApplication['status'] ?>">
                <strong>Status: </strong><?= $infoApplication['status'] ?>
            </p>

            <?php if ($infoApplication['status'] !== 'waiting' && !empty($infoApplication['status_reason'])): ?>
                <label for="application-response">Message from company:</label>
                <div id="application-response">
                    <?= htmlspecialchars($infoApplication['status_reason']) ?>
                </div>
            <?php endif; ?>

            <div class="status-footer">
                <?php if (!empty($infoApplication['cv_path'])): ?>
                    <div class="apply-button">
                        <a class="btn"
                            href="<?= 'http://localhost:8000/public/files/applications/cv/' . $infoApplication['cv_path'] ?>"
                            target="_blank">Your CV</a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($infoApplication['video_path'])): ?>
                    <div class="apply-button">
                        <a class="btn"
                            href="<?= 'http://localhost:8000/public/files/applications/videos/' . $infoApplication['video_path'] ?>"
                            target="_blank">Your Video</a>
                    </div>
                <?php endif; ?>
            </div>
            <? if (!empty($infoApplication['video_path'])) {
                ?>
                <video controls>
                    <source
                        src="<?= 'http://localhost:8000/public/files/applications/videos/' . $infoApplication['video_path'] ?>"
                        type="video/mp4">
                </video>
            <?
            } ?>
            <? if (!empty($infoApplication['cv_path'])) {
                ?>
                <embed class="" src="<?= 'http://localhost:8000/public/files/applications/cv/' . $infoApplication['cv_path'] ?>"
                    type="application/pdf">
            <?
            } ?>
        </div>
    <?php endif; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="http://localhost:8000/public/js/pages/jobdetail.js"></script>

<?php require_once __DIR__ . "/../template/footer.php" ?>