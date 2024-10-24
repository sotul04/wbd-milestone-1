<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/history.css">

<section id="history">
    <div class="jobs-container container">
        <?php
        // Pastikan variabel $jobs terdefinisi dan berisi daftar pekerjaan
        if (isset($jobs) && count($jobs) > 0) {
            ?>
            <h2>Your applied job(s).</h2>
            <?
            foreach ($jobs as $job) {
                ?>
                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title"><?= htmlspecialchars($job['posisi']) ?></h3>
                        <p class="company-name"><?= htmlspecialchars($job['company_name']) ?></p>
                    </div>
                    <div class="job-body">
                        <p><strong>Type:</strong> <?= htmlspecialchars($job['jenis_pekerjaan']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($job['jenis_lokasi']) ?></p>
                    </div>
                    <div class="job-status">
                        <div class="status <?= $job['is_open'] ? 'open' : 'close' ?>">
                            <p><strong><?= $job['is_open'] ? 'Open' : 'Closed' ?></strong></p>
                        </div>
                        <p class="status-application <?= $job['status']?>"><?= $job['status']?></p>
                    </div>
                    <div class="job-footer">
                        <p><small>Posted on: <?= date('F j, Y', strtotime($job['created_at'])) ?></small></p>
                        <a href="http://localhost:8000/job/<?= $job['lowongan_id'] ?>" class="link-btn">Detail</a>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <p>No Job Available</p>
            <?php
        }
        ?>
    </div>
</section>

<?php require_once __DIR__ . "/../template/footer.php" ?>