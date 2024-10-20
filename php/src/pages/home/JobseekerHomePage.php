<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="stylesheet" href="http://localhost:8000/public/css/pages/jobseeker.css" />

<main id="main">
    <section class="grid-container container">
        <aside class="sidebar">
            <div class="sidebar-item shadow-5">
                <div class="sidebar-top"></div>
                <div class="img">
                    <img src="http://localhost:8000/public/assets/icons/Camera.ico" alt="Camera icon">
                </div>
                <div class="sidebar-main">
                    <p>Welcome, <?= isset($name) ? htmlspecialchars($name) : 'GUEST' ?>!</p>
                </div>
                <nav>
                    <a href="http://localhost:8000/history">Application History</a>
                </nav>
            </div>
            <div class="sidebar-item shadow-5">
                <form class="filter-form" id="filter-form">
                    <div class="filter-group">
                        <label for="locationType">Location Type:</label>
                        <select name="locationType" id="locationType">
                            <option value="">All</option>
                            <option value="on-site">On-site</option>
                            <option value="hybrid">Hybrid</option>
                            <option value="remote">Remote</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="jobType">Job Type:</label>
                        <select name="jobType" id="jobType">
                            <option value="">All</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="sort">Sort by:</label>
                        <select name="sort" id="sort">
                            <option value="">Default</option>
                            <option value="ASC">Older First</option>
                            <option value="DESC">Newest First</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="search">Search:</label>
                        <input type="text" name="search" id="search" placeholder="Search">
                    </div>
                </form>
            </div>
        </aside>

        <div class="main-content">
            <!-- Job Listings -->
            <div class="job-listings" id="job-listings">
                <!-- Job Listings will be injected here -->
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination">
                <!-- Pagination will be injected here -->
            </div>
        </div>
    </section>
</main>

<script src="http://localhost:8000/public/js/pages/jobseeker.js"></script>

<?php require_once __DIR__ . "/../template/footer.php" ?>
