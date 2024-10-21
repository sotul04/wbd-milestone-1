<?php require_once __DIR__ . "/../template/header.php" ?>
<?php require_once __DIR__ . "/../template/navbar.php" ?>

<link rel="stylesheet" href="<? BASE_URL ?>/public/css/pages/companypage.css" />

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

        <div class="main-content">
            <!-- Job Listings -->
            <div class="job-listings">
                <?
                if (isset($content)) {
                    foreach ($content['jobs'] as $key => $item) {
                        ?>
                        <div class="job-card">
                            <div class="job-header">
                                <h3 class="job-title"><?= htmlspecialchars($item['posisi']) ?></h3>
                                <p class="company-name"><?= htmlspecialchars($item['company_name']) ?></p>
                            </div>
                            <div class="job-body">
                                <p><strong>Type:</strong> <?= htmlspecialchars($item['jenis_pekerjaan']) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($item['jenis_lokasi']) ?></p>
                                <p class="job-description"><?= htmlspecialchars($item['deskripsi']) ?></p>
                            </div>
                            <div class="job-footer">
                                <p><small>Posted on: <?= date('F j, Y', strtotime($item['created_at'])) ?></small></p>
                                <button class="apply-btn">Apply Now</button>
                            </div>
                        </div>
                        <?
                    }
                    if (count($content['jobs']) === 0) {
                        ?>
                        <p>No Job Available</p>
                        <?
                    }
                }
                ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php
                $totalPages = isset($content) ? $content['total_pages'] : 1;
                $currentPage = isset($content) ? $content['current_page'] : 1;

                echo "<p>$currentPage of $totalPages</p>";
                // Previous button (disabled if on the first page)
                if ($currentPage > 1) {
                    echo "<button data-page='" . ($currentPage - 1) . "' class='prev-btn'>Prev</button>";
                } else {
                    echo "<button disabled class='prev-btn'>Prev</button>";
                }

                // Pagination numbers
                $minPage = max(1, $currentPage - 1);
                $maxPage = min($totalPages, $currentPage + 1);

                for ($i = $minPage; $i <= $maxPage; $i++) {
                    if ($i == $currentPage) {
                        echo "<button class='active' disabled>$i</button>";
                    } else {
                        echo "<button data-page='$i'>$i</button>";
                    }
                }

                // Next button (disabled if on the last page)
                if ($currentPage < $totalPages) {
                    echo "<button data-page='" . ($currentPage + 1) . "' class='next-btn'>Next</button>";
                } else {
                    echo "<button disabled class='next-btn'>Next</button>";
                }
                ?>
            </div>
        </div>
    </section>
</main>

<script src="http://localhost:8000/public/js/pages/companyseeker.js"></script>

<?php require_once __DIR__ . "/../template/footer.php" ?>