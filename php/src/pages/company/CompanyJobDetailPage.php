<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="preload" href="/public/css/pages/companyjobdetail.css" as="style">
<link rel="stylesheet" href="/public/css/pages/companyjobdetail.css">
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<main id="main">
    <section id="job-detail" aria-labelledby="job-title">
        <div class="container job-container shadow-4">
            <h1 id="job-title" class="job-title"><?= htmlspecialchars($jobDetail['posisi']) ?></h1>
            <div class="job-info-container" role="complementary">
                <p class="job-info"><?= htmlspecialchars($jobDetail['jenis_lokasi']) ?></p>
                <p class="job-info"><?= htmlspecialchars($jobDetail['jenis_pekerjaan']) ?></p>
            </div>
            <div class="company-details">
                <h2 class="company-name"><?= htmlspecialchars($jobDetail['company_name']) ?></h2>
                <p><img alt="Company location icon"
                        src="/public/assets/icons/Location.ico"><?= htmlspecialchars($jobDetail['lokasi']) ?></p>
                <p><img alt="About company icon"
                        src="/public/assets/icons/About.ico"><?= htmlspecialchars($jobDetail['about']) ?></p>
            </div>
            <div class="job-description">
                <label>Job's description:</label>
                <div id="job-description-editor" role="document">
                    <?= htmlspecialchars_decode($jobDetail['deskripsi']) ?>
                </div>
            </div>
            <div class="job-posted-date">
                <p>Posted on: <?= htmlspecialchars(date('F j, Y', strtotime($jobDetail['created_at']))) ?></p>
                <div id="status-indicator" class="status <?= $jobDetail['is_open'] ? 'open' : 'close' ?>">
                    <p><strong><?= $jobDetail['is_open'] ? 'Open' : 'Closed' ?></strong></p>
                </div>
            </div>

            <div class="photos" aria-label="Job Photos">
                <?php if (isset($attachments)): ?>
                    <?php foreach ($attachments as $photo): ?>
                        <img loading="lazy" src="<?= '/public/files/attachments/' . htmlspecialchars($photo['file_path']) ?>"
                            class="shadow-5" alt="Photo of <?= htmlspecialchars($jobDetail['posisi']) ?>" width="600"
                            height="400">
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="modif-buttons" aria-label="Job modification buttons">
                <a id="edit-job" class="btn edit"
                    href="/company/job/<?= htmlspecialchars($jobDetail['lowongan_id']) ?>/edit">Edit</a>
                <button aria-label="Delete this job" id="delete-button"
                    class="btn btn-destroy delete-job">Delete</button>
                <button aria-label="Toggle job status" data-job-id="<?= htmlspecialchars($jobDetail['lowongan_id']) ?>"
                    id="toggle-button" type="submit"
                    class="btn <?= $jobDetail['is_open'] ? 'close' : 'open' ?> toggle-job">
                    <?= $jobDetail['is_open'] ? 'Close' : 'Open' ?>
                </button>
            </div>
        </div>
    </section>

    <section id="applicant-detail" aria-labelledby="applicant">
        <div class="container applicant-container shadow-4">
            <h1 id="applicants-title" class="applicants-title">Applicants</h1>
            <div class="applicant-grid">
                <?php if (count($infoApplicants) > 0): ?>
                    <div class="applicant-header">Name</div>
                    <div class="applicant-header">Status</div>
                    <div class="applicant-header">Details</div>
                    <?php foreach ($infoApplicants as $applicant): ?>
                        <div class="applicant-cell" id="applicant-name">
                            <p><?= htmlspecialchars($applicant['nama_pelamar']) ?></p>
                        </div>
                        <div class="applicant-cell <?= htmlspecialchars($applicant['status_pelamar']) ?>" id="applicant-status">
                            <p><?= htmlspecialchars($applicant['status_pelamar']) ?></p>
                        </div>
                        <div class="applicant-cell">
                            <a href="<?= '/company/job/' . htmlspecialchars($applicant['lowongan_id']) . '/applicant/' . htmlspecialchars($applicant['userid']) ?>"
                                class="btn btn-primary">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No Applicant yet</p>
                <?php endif; ?>
            </div>
            <?php if (count($infoApplicants) > 0): ?>
                <div class="buttons">
                    <button id="export-csv" class="btn btn-secondary">Export CSV</button>
                    <button id="export-excel" class="btn btn-secondary">Export Excel</button>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="/public/js/pages/companyjobdetail.js"></script>

<?php require_once __DIR__ . "/../template/footer.php" ?>