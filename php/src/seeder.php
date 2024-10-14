<!-- <?php
// Include your DBConnection class file
require_once 'database/DBConnection.php'; // Update the path as necessary

try {
    // Get the database connection
    $pdo = DBConnection::getConnection();

    // Seed users table
    $usersSql = "INSERT INTO users (email, password, role, nama) VALUES
        ('jobseeker1@example.com', 'password1', 'jobseeker', 'Job Seeker 1'),
        ('company1@example.com', 'password1', 'company', 'Company 1'),
        ('jobseeker2@example.com', 'password2', 'jobseeker', 'Job Seeker 2'),
        ('company2@example.com', 'password2', 'company', 'Company 2')";
    $pdo->exec($usersSql);

    // Seed company_details table
    $companyDetailsSql = "INSERT INTO company_details (user_id, lokasi, about) VALUES
        (2, 'Jakarta', 'Company 1 is a tech startup based in Jakarta.'),
        (4, 'Bandung', 'Company 2 is a well-established company in Bandung.')";
    $pdo->exec($companyDetailsSql);

    // Seed lowongan table
    $lowonganSql = "INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi) VALUES
        (2, 'Software Engineer', 'We are looking for a software engineer.', 'Full-time', 'Remote'),
        (2, 'UI/UX Designer', 'We are looking for a creative UI/UX designer.', 'Full-time', 'On-site'),
        (4, 'Data Analyst', 'We are looking for a data analyst to join our team.', 'Contract', 'Remote')";
    $pdo->exec($lowonganSql);

    // Seed attachments_lowongan table
    $attachmentsLowonganSql = "INSERT INTO attachments_lowongan (lowongan_id, file_path) VALUES
        (1, '/files/software_engineer_description.pdf'),
        (2, '/files/ui_ux_designer_description.pdf'),
        (3, '/files/data_analyst_description.pdf')";
    $pdo->exec($attachmentsLowonganSql);

    // Seed lamaran table
    $lamaranSql = "INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path, status, status_reason) VALUES
        (1, 1, '/files/jobseeker1_cv.pdf', '/files/jobseeker1_video.mp4', 'waiting', ''),
        (3, 2, '/files/jobseeker2_cv.pdf', '/files/jobseeker2_video.mp4', 'waiting', '')";
    $pdo->exec($lamaranSql);

    echo "Database seeded successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> -->
