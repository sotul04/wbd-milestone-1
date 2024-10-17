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
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($pdo) {
        echo "Connected to the database successfully!\n";

        // Prepare user data
        $users = [];
        $companyIds = [];
        $jobSeekerIds = [];

        for ($i = 1; $i <= 4; $i++) {
            $userId = $pdo->query("SELECT uuid_generate_v4()")->fetchColumn();
            $companyIds[] = $userId;
            $users[] = [
                'user_id' => $userId,
                'email' => "c{$i}@c.com",
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'company',
                'nama' => "Company{$i} THEGREATEST"
            ];
        }

        for ($i = 1; $i <= 16; $i++) {
            $userId = $pdo->query("SELECT uuid_generate_v4()")->fetchColumn();
            $jobSeekerIds[] = $userId;
            $users[] = [
                'user_id' => $userId,
                'email' => "js{$i}@js.com",
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'jobseeker',
                'nama' => "JobSeeker{$i} THEBEST"
            ];
        }

        // Insert data into users table
        $userInsertQuery = $pdo->prepare("INSERT INTO users (user_id, email, password, role, nama) VALUES (?, ?, ?, ?, ?)");
        foreach ($users as $user) {
            $userInsertQuery->execute([$user['user_id'], $user['email'], $user['password'], $user['role'], $user['nama']]);
        }

        // Insert into company_details table
        $companyDetailsInsertQuery = $pdo->prepare("INSERT INTO company_details (user_id, lokasi, about) VALUES (?, ?, ?)");
        $lokasi = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta'];
        foreach ($companyIds as $index => $companyId) {
            $about = "Company " . ($index + 1) . " is a tech startup based in {$lokasi[$index]}.";
            $companyDetailsInsertQuery->execute([$companyId, $lokasi[$index], $about]);
        }

        $jenisLokasi = ['on-site', 'hybrid', 'remote'];
        $jenisPekerjaan = ['Full-time', 'Part-time', 'Internship'];

        // Prepare and insert data into lowongan table with random created_at
        $lowonganInsertQuery = $pdo->prepare("INSERT INTO lowongan (lowongan_id, company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Define a base date, one year ago from now
        $baseDate = new DateTime();
        $baseDate->modify('-1 year');

        $lowonganIds = [];
        foreach ($companyIds as $companyId) {
            for ($j = 1; $j <= 20; $j++) {
                $lowonganId = $pdo->query("SELECT uuid_generate_v4()")->fetchColumn();
                $lowonganIds[] = $lowonganId;
                $posisi = "Position {$j} for Company";
                $deskripsi = "Job description for {$posisi}.";
                $randomJenisPekerjaan = $jenisPekerjaan[array_rand($jenisPekerjaan)];
                $randomJenisLokasi = $jenisLokasi[array_rand($jenisLokasi)];

                // Generate a random date within the last year
                $randomDate = clone $baseDate;
                $randomDate->modify('+' . rand(0, 365) . ' days');
                $randomDate->modify('+' . rand(0, 23) . ' hours');
                $randomDate->modify('+' . rand(0, 59) . ' minutes');
                $randomDate->modify('+' . rand(0, 59) . ' seconds');

                $formattedDate = $randomDate->format('Y-m-d H:i:s');

                $lowonganInsertQuery->execute([$lowonganId, $companyId, $posisi, $deskripsi, $randomJenisPekerjaan, $randomJenisLokasi, $formattedDate]);
            }
        }

        // Insert into attachments_lowongan table
        $attachmentsInsertQuery = $pdo->prepare("INSERT INTO attachments_lowongan (attachment_id, lowongan_id, file_path) VALUES (?, ?, ?)");
        foreach ($lowonganIds as $index => $lowonganId) {
            $attachmentId = $pdo->query("SELECT uuid_generate_v4()")->fetchColumn();
            $attachmentsInsertQuery->execute([$attachmentId, $lowonganId, "/files/attachment" . ($index + 1) . ".pdf"]);
        }

        // Insert into lamaran table
        $lamaranInsertQuery = $pdo->prepare("INSERT INTO lamaran (lamaran_id, user_id, lowongan_id, cv_path, video_path, status, status_reason) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($jobSeekerIds as $index => $jobSeekerId) {
            $appliedLowonganIds = $lowonganIds;
            shuffle($appliedLowonganIds);
            $selectedLowonganIds = array_slice($appliedLowonganIds, 0, 5);
            foreach ($selectedLowonganIds as $lowonganId) {
                $lamaranId = $pdo->query("SELECT uuid_generate_v4()")->fetchColumn();
                $lamaranInsertQuery->execute([
                    $lamaranId,
                    $jobSeekerId,
                    $lowonganId,
                    "/files/jobseeker" . ($index + 1) . "_cv.pdf",
                    "/files/jobseeker" . ($index + 1) . "_video.mp4",
                    'waiting',
                    ''
                ]);
            }
        }

        echo "Database seeded successfully with UUIDs and random timestamps!\n";
    } else {
        echo "Connection failed.\n";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}