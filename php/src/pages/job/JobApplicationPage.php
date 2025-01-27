<?php require_once __DIR__ . "/../template/header.php"; ?>
<?php require_once __DIR__ . "/../template/navbar.php"; ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/jobapplication.css">

<section id="job-application">
    <div class="container">
        <a class="back-button" href="http://localhost:8000/job/<?= htmlspecialchars($jobID) ?>"><img
                src="http://localhost:8000/public/assets/icons/Left.ico" alt="Back Icon"> Back</a>
    </div>
    <div class="application-container container shadow-5">
        <h1>Apply Job</h1>

        <form id="jobApplicationForm" method="POST" enctype="multipart/form-data"
            action="http://localhost:8000/job/apply/<?= htmlspecialchars($jobID) ?>">
            <div class="form-group">
                <label for="cv">Upload your CV - Max 10MB</label>
                <input type="file" id="cv" name="cv" accept=".pdf" required>
            </div>

            <div class="form-group">
                <label for="video">Upload your video (Optional) - Max 50MB</label>
                <input type="file" id="video" name="video" accept=".mp4">
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">Apply</button>
        </form>
    </div>
</section>

<script src="http://localhost:8000/public/js/pages/jobapplication.js"></script>

<?php require_once __DIR__ . "/../template/footer.php"; ?>