<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="stylesheet" href="<? BASE_URL ?>/public/css/pages/jobseekerpage.css" />

<main>
    <section class="grid-container container">
        <aside class="sidebar">
            <div class="sidebar-item">
                <div class="sidebar-top"></div>
                <div class="img">
                    <img src="<? BASE_URL ?>/public/assets/icons/Camera.ico" alt="Camera icon">
                </div>
                <div class="sidebar-main">
                    <p>Welcome, <?= isset($name) ? htmlspecialchars($name) : 'GUEST' ?>!</p>
                </div>
                <nav></nav>
            </div>

            <div class="sidebar-item">
                <form class="filter-form" id="filter-form">
                    <div class="filter-group">
                        <label for="locationType">Location Type:</label>
                        <select name="locationType" id="locationType">
                            <option value="">All</option>
                            <option value="on-site" <?= isset($_GET['locationType']) && $_GET['locationType'] == 'on-site' ? 'selected' : '' ?>>On-site</option>
                            <option value="hybrid" <?= isset($_GET['locationType']) && $_GET['locationType'] == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                            <option value="remote" <?= isset($_GET['locationType']) && $_GET['locationType'] == 'remote' ? 'selected' : '' ?>>Remote</option>
                        </select>
                    </div>
    
                    <div class="filter-group">
                        <label for="jobType">Job Type:</label>
                        <select name="jobType" id="jobType">
                            <option value="">All</option>
                            <option value="Full-time" <?= isset($_GET['jobType']) && $_GET['jobType'] == 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                            <option value="Part-time" <?= isset($_GET['jobType']) && $_GET['jobType'] == 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                            <option value="Internship" <?= isset($_GET['jobType']) && $_GET['jobType'] == 'Internship' ? 'selected' : '' ?>>Internship</option>
                        </select>
                    </div>
    
                    <div class="filter-group">
                        <label for="sort">Sort by:</label>
                        <select name="sort" id="sort">
                            <option value="" <?= isset($_GET['sort']) && $_GET['sort'] == '' ? 'selected' : '' ?>>Default</option>
                            <option value="ASC" <?= isset($_GET['sort']) && $_GET['sort'] == 'ASC' ? 'selected' : '' ?>>Older First</option>
                            <option value="DESC" <?= isset($_GET['sort']) && $_GET['sort'] == 'DESC' ? 'selected' : '' ?>>Newest First</option>
                        </select>
                    </div>
    
                    <div class="filter-group">
                        <label for="search">Search:</label>
                        <input type="text" name="search" id="search"
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                            placeholder="Search jobs...">
                    </div>
                </form>
            </div>
        </aside>
    </section>
</main>

<?php require_once __DIR__ . "/../template/footer.php" ?>