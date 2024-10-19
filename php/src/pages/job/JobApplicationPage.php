<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/jobapplication.css">

<section id="job-application">
    <div class="container">
        <a class="back-button" href="http://localhost:8000/job/<?= $jobID?>"><img src="http://localhost:8000/public/assets/icons/Left.ico"> Back</a>
    </div>
    <div class="application-container container shadow-5">
        <h1>Apply Job</h1>

        <form id="jobApplicationForm">
            <div class="form-group">
                <label for="cv">Upload your CV</label>
                <input type="file" id="cv" name="cv" accept=".pdf" pse required>
            </div>

            <div class="form-group">
                <label for="video">Upload your video (Optional)</label>
                <input type="file" id="video" name="video" accept=".mp4" >
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">Aplly</button>
        </form>

    </div>
</section>

<script src="http://localhost:8000/public/js/pages/jobapplication.js"></script>

<?php require_once __DIR__ . "/../template/footer.php" ?>