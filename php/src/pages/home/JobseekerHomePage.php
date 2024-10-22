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
                    <?php
                    if (isset($name)) {
                        ?>
                        <a href="http://localhost:8000/history">Application History</a>
                    <?php
                    } else {
                        ?>
                        <a href="http://localhost:8000/user/login">Login</a>
                        <a href="http://localhost:8000/user/register">Register</a>
                    <?php
                    }
                    ?>
                </nav>
            </div>
            <div class="sidebar-item shadow-5">
                <form class="filter-form" id="filter-form">
                    <div class="filter-group">
                        <label for="jobType">Job Type:</label>
                        <div class="item-filter">
                            <label for="locationType-onsite">On-site</label>
                            <input type="checkbox" name="locationType" value="on-site" id="locationType-onsite">
                        </div>
                        <div class="item-filter">
                            <label for="locationType-hybrid">Hybrid</label>
                            <input type="checkbox" name="locationType" value="hybrid" id="locationType-hybrid">
                        </div>
                        <div class="item-filter">
                            <input type="checkbox" name="locationType" value="remote" id="locationType-remote">
                            <label for="locationType-remote">Remote</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="jobType">Job Type:</label>
                        <div class="item-filter">
                            <input type="checkbox" name="jobType" value="Full-time" id="jobType-fulltime">
                            <label for="jobType-fulltime">Full-time</label>
                        </div>
                        <div class="item-filter">
                            <input type="checkbox" name="jobType" value="Part-time" id="jobType-parttime">
                            <label for="jobType-parttime">Part-time</label>
                        </div>
                        <div class="item-filter">
                            <input type="checkbox" name="jobType" value="Internship" id="jobType-internship">
                            <label for="jobType-internship">Internship</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="sort">Sort by:</label>
                        <select name="sort" id="sort">
                            <option value="" <?= $sort === '' ? 'selected' : ''; ?>>Default</option>
                            <option value="ASC" <?= $sort === 'ASC' ? 'selected' : ''; ?>>Older First</option>
                            <option value="DESC" <?= $sort === 'DESC' ? 'selected' : ''; ?>>Newest First</option>
                        </select>
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