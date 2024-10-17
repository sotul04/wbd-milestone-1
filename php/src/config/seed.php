<?php

try {
    // Retrieve database credentials from environment variables
    $host = getenv('DB_HOST');
    $db = getenv('DB_DATABASE');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    $port = getenv('DB_PORT') ?: 5432;

    // Connect to PostgreSQL database
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";
    $pdo = new PDO($dsn);

    if ($pdo) {
        echo "Connected to the database successfully!\n";

        // Prepare user data
        $users = [];
        for ($i = 1; $i <= 4; $i++) { // Doubling companies
            // Add company users
            $users[] = [
                'email' => "c{$i}@c.com",
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'company',
                'nama' => "Company{$i} THEGREATEST"
            ];
        }
        
        for ($i = 1; $i <= 16; $i++) { // Doubling job seekers
            // Add job seekers
            $users[] = [
                'email' => "js{$i}@js.com",
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'jobseeker',
                'nama' => "JobSeeker{$i} THEBEST"
            ];
        }

        // Insert data into users table
        $userInsertQuery = "INSERT INTO users (email, password, role, nama) VALUES ";
        $userValues = [];
        foreach ($users as $user) {
            $userValues[] = "('{$user['email']}', '{$user['password']}', '{$user['role']}', '{$user['nama']}')";
        }
        $userInsertQuery .= implode(', ', $userValues);
        $pdo->exec($userInsertQuery);

        // Get user IDs after insert
        $companyIds = [1, 2, 3, 4]; // Updated company IDs
        $jobSeekerIds = range(5, 20); // Updated job seeker IDs (assuming they are 5 to 20)

        // Insert data into company_details table
        $companyDetailsInsertQuery = "INSERT INTO company_details (user_id, lokasi, about) VALUES ";
        $companyDetailsValues = [];
        foreach ($companyIds as $companyId) {
            $lokasi = $companyId === 1 ? 'Jakarta' : ($companyId === 2 ? 'Bandung' : ($companyId === 3 ? 'Surabaya' : 'Yogyakarta'));
            $about = "Company {$companyId} is a tech startup based in {$lokasi}.";
            $companyDetailsValues[] = "($companyId, '$lokasi', '$about')";
        }
        $companyDetailsInsertQuery .= implode(', ', $companyDetailsValues);
        $pdo->exec($companyDetailsInsertQuery);

        // Prepare and insert data into lowongan table
        $lowonganInsertQuery = "INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi) VALUES ";
        $lowonganValues = [];
        foreach ($companyIds as $companyId) {
            for ($j = 1; $j <= 20; $j++) { // Doubling job openings per company
                $posisi = "Position {$j} for Company {$companyId}";
                $deskripsi = "Job description for {$posisi}.";
                $lowonganValues[] = "($companyId, '$posisi', '$deskripsi', 'Full-time', 'remote')";
            }
        }
        $lowonganInsertQuery .= implode(', ', $lowonganValues);
        $pdo->exec($lowonganInsertQuery);

        // Insert data into attachments_lowongan table
        $attachmentsInsertQuery = "INSERT INTO attachments_lowongan (lowongan_id, file_path) VALUES ";
        $attachmentsValues = [];
        for ($k = 1; $k <= 20; $k++) { // Doubling the number of attachments
            $attachmentsValues[] = "($k, '/files/attachment{$k}.pdf')";
        }
        $attachmentsInsertQuery .= implode(', ', $attachmentsValues);
        $pdo->exec($attachmentsInsertQuery);

        // Insert data into lamaran table
        $lamaranInsertQuery = "INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path, status, status_reason) VALUES ";
        $lamaranValues = [];
        foreach ($jobSeekerIds as $jobSeekerId) {
            // Each job seeker applies to at least 5 job openings
            $appliedLowonganIds = range(1, 80); // Assuming lowongan IDs are from 1 to 80 (4 companies x 20 lowongan)
            shuffle($appliedLowonganIds); // Randomize the applications
            $selectedLowonganIds = array_slice($appliedLowonganIds, 0, 5); // Select 5 random lowongan
            
            foreach ($selectedLowonganIds as $lowonganId) {
                $lamaranValues[] = "($jobSeekerId, $lowonganId, '/files/jobseeker{$jobSeekerId}_cv.pdf', '/files/jobseeker{$jobSeekerId}_video.mp4', 'waiting', '')";
            }
        }
        $lamaranInsertQuery .= implode(', ', $lamaranValues);
        $pdo->exec($lamaranInsertQuery);

        echo "Database seeded successfully!\n";
    } else {
        echo "Connection failed.\n";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
