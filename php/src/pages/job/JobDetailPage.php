<?php require_once __DIR__ . "/../template/header.php"; ?>
<?php require_once __DIR__ . "/../template/navbar.php"; ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/jobdetail.css">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<section id="job-detail" aria-labelledby="job-title">
    <div class="container job-container shadow-4">
        <h1 id="job-title" class="job-title"><?= htmlspecialchars($jobDetail['posisi']) ?></h1>
        <div class="job-info-container" role="complementary">
            <p class="job-info"><?= htmlspecialchars($jobDetail['jenis_lokasi']) ?></p>
            <p class="job-info"><?= htmlspecialchars($jobDetail['jenis_pekerjaan']) ?></p>
        </div>
        <div class="company-details">
            <h2 class="company-name"><?= htmlspecialchars($jobDetail['company_name']) ?></h2>
            <p><img alt="Location icon" src="http://localhost:8000/public/assets/icons/Location.ico"><?= htmlspecialchars($jobDetail['lokasi'])?></p>
            <p><img alt="Location icon" src="http://localhost:8000/public/assets/icons/About.ico"><?= htmlspecialchars($jobDetail['about'])?></p>
        </div>
        <div class="job-description">
            <label for="job-description-editor">Job's description:</label>
            <div id="job-description-editor" role="document">
                <?= htmlspecialchars($jobDetail['deskripsi']) ?>
            </div>
        </div>
        <div class="job-posted-date">
            <p>Posted on: <?= htmlspecialchars(date('F j, Y', strtotime($jobDetail['created_at']))) ?></p>
            <p><strong><?= $jobDetail['is_open'] ? 'Open' : 'Closed'?></strong></p>
        </div>

        <!-- Apply Button for Job Seeker -->
        <?php if ($role === 'jobseeker' && empty($infoApplication) && $jobDetail['is_open']): ?>
            <div class="apply-button">
                <a href="http://localhost:8000/job/<?= htmlspecialchars($jobDetail['lowongan_id']) ?>/application"
                    class="btn" aria-label="Apply for <?= htmlspecialchars($jobDetail['posisi']) ?>">Apply Now</a>
            </div>
        <?php endif; ?>

        <!-- Job Photos -->
        <div class="photos" aria-label="Job Photos">
            <?php if (isset($attachments)): ?>
                <?php foreach ($attachments as $photo): ?>
                    <img src="<?= 'http://localhost:8000/public/files/attachments/' . htmlspecialchars($photo['file_path']) ?>"
                        class="shadow-5" alt="Photo of <?= htmlspecialchars($jobDetail['posisi']) ?>">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Application Section -->
    <?php if (isset($infoApplication) && $infoApplication !== false): ?>
        <div class="container job-container application shadow-4" role="region" aria-labelledby="application-title">
            <h2 id="application-title" class="application-title">Your Application</h2>
            <p class="application-status <?= htmlspecialchars($infoApplication['status']) ?>">
                <strong>Status: </strong><?= htmlspecialchars($infoApplication['status']) ?>
            </p>

            <?php if ($infoApplication['status'] !== 'waiting' && !empty($infoApplication['status_reason'])): ?>
                <label>Message from company:</label>
                <div id="application-response" aria-live="polite">
                    <?= htmlspecialchars($infoApplication['status_reason']) ?>
                </div>
            <?php endif; ?>

            <div class="status-footer">
                <?php if (!empty($infoApplication['cv_path'])): ?>
                    <div class="apply-button">
                        <a class="btn"
                            href="<?= 'http://localhost:8000/storage/files/cv/' . htmlspecialchars($infoApplication['cv_path']) ?>"
                            target="_blank" aria-label="View your CV">Your CV</a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($infoApplication['video_path'])): ?>
                    <div class="apply-button">
                        <a class="btn"
                            href="<?= 'http://localhost:8000/storage/files/videos/' . htmlspecialchars($infoApplication['video_path']) ?>"
                            target="_blank" aria-label="View your video">Your Video</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Video Playback -->
            <?php if (!empty($infoApplication['video_path'])): ?>
                <video controls class="video-player" aria-label="Video application">
                    <source
                        src="<?= 'http://localhost:8000/storage/files/videos/' . htmlspecialchars($infoApplication['video_path']) ?>"
                        type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>

            <!-- CV Display -->
            <?php if (!empty($infoApplication['cv_path'])): ?>
                <embed src="<?= 'http://localhost:8000/storage/files/cv/' . htmlspecialchars($infoApplication['cv_path']) ?>"
                    type="application/pdf" width="600" height="400" aria-label="Your CV" alt="Your CV">
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="http://localhost:8000/public/js/pages/jobdetail.js"></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>