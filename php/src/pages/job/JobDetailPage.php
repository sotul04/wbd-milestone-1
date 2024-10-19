<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/jobdetail.css">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<section id="job-detail">
    <div class="container job-container">
        <h1 class="job-title"><?= htmlspecialchars($jobDetail['posisi']) ?></h1>
        <div class="company-info">
            <h2 class="company-name"><?= htmlspecialchars($jobDetail['company_name']) ?></h2>
            <p class="job-location"><strong>Location: </strong><?= htmlspecialchars($jobDetail['jenis_lokasi']) ?></p>
        </div>
        <div class="job-description">
            <h2>Description:</h2>
            <div id="job-description-editor">
                <?= $jobDetail['deskripsi'] ?>
            </div>
        </div>
        <div class="job-type">
            <p><strong>Type:</strong> <?= htmlspecialchars($jobDetail['jenis_pekerjaan']) ?></p>
        </div>
        <div class="job-posted-date">
            <p>Posted on: <?= htmlspecialchars(date('F j, Y', strtotime($jobDetail['created_at']))) ?>
            </p>
        </div>
        <?
        if ($role === 'jobseeker') {
            ?>
            <div class="apply-button">
                <a href="http://localhost:8000/job/<?= htmlspecialchars($jobDetail['lowongan_id']) ?>/application"
                    class="btn">Apply Now</a>
            </div>
        <?
        }
        ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="http://localhost:8000/public/js/pages/jobdetail.js"></script>

<?php require_once __DIR__ . "/../template/footer.php" ?>