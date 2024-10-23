<?php

class JobModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getJobById($lowonganId)
    {
        $this->db->query("SELECT * FROM lowongan WHERE lowongan_id = :lowonganId");
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->single();
    }

    public function getJobByCompanyId($companyId, $page, $sort, $locationType, $jobType, $search)
    {
        $rowperpage = ROWS_PER_PAGE;
        $offset = ($page - 1) * $rowperpage;

        // Updated query to join with users table and filter by company_id
        $query = "SELECT lowongan.*, users.nama as company_name 
                  FROM lowongan
                  JOIN users ON lowongan.company_id = users.user_id
                  WHERE lowongan.company_id = ?";

        if (!empty($locationType)) {
            $placeholders = implode(',', array_fill(0, count($locationType), '?'));
            $query .= " AND jenis_lokasi IN ($placeholders)";
        }
        if (!empty($jobType)) {
            $placeholders = implode(',', array_fill(0, count($jobType), '?'));
            $query .= " AND jenis_pekerjaan IN ($placeholders)";
        }
        if (!empty($search)) {
            $query .= " AND posisi ILIKE ?";
        }

        switch ($sort) {
            case 'DESC':
                $query .= " ORDER BY created_at DESC";
                break;
            case 'ASC':
                $query .= " ORDER BY created_at ASC";
                break;
            default:
                break;
        }
        $query .= " LIMIT ? OFFSET ?";

        $this->db->query($query);

        $index = 1;
        $this->db->bind($index++, $companyId);
        // Bind the parameter to the query
        // $this->db->bind(':companyId', $companyId);

        foreach ($locationType as $loc) {
            $this->db->bind($index, $loc);
            $index++;
        }

        foreach ($jobType as $job) {
            $this->db->bind($index, $job);
            $index++;
        }

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $this->db->bind($index++, $searchTerm);
        }

        $this->db->bind($index++, (int) $rowperpage, PDO::PARAM_INT);
        $this->db->bind($index, (int) $offset, PDO::PARAM_INT);

        $jobs = $this->db->resultSet();

        // Count query
        $countQuery = "SELECT COUNT(*) as total FROM lowongan JOIN users ON lowongan.company_id = users.user_id
                    WHERE lowongan.company_id = ?";

        if (!empty($locationType)) {
            $placeholders = implode(',', array_fill(0, count($locationType), '?'));
            $countQuery .= " AND jenis_lokasi IN ($placeholders)";
        }
        if (!empty($jobType)) {
            $placeholders = implode(',', array_fill(0, count($jobType), '?'));
            $countQuery .= " AND jenis_pekerjaan IN ($placeholders)";
        }
        if (!empty($search)) {
            $countQuery .= " AND posisi ILIKE ?";
        }

        $this->db->query($countQuery);

        $index = 1;
        $this->db->bind($index++, $companyId);

        foreach ($locationType as $loc) {
            $this->db->bind($index++, $loc);
        }

        foreach ($jobType as $job) {
            $this->db->bind($index++, $job);
        }

        if (!empty($search)) {
            $this->db->bind($index++, $searchTerm);
        }

        $countResult = $this->db->single();

        $totalRow = $countResult ? $countResult['total'] : 0;

        $totalPages = ceil($totalRow / $rowperpage);

        return [
            'jobs' => $jobs,
            'total_pages' => $totalPages,
            'current_page' => $page
        ];
    }

    public function getJobs($page, $sort, $locationTypes, $jobTypes, $search)
    {
        $rowperpage = ROWS_PER_PAGE;
        $offset = ($page - 1) * $rowperpage;

        // Base query
        $query = "SELECT lowongan.*, users.nama as company_name 
                    FROM lowongan
                    JOIN users ON lowongan.company_id = users.user_id
                    WHERE 1=1";

        // Handle multiple location types
        if (!empty($locationTypes)) {
            $placeholders = implode(',', array_fill(0, count($locationTypes), '?'));
            $query .= " AND jenis_lokasi IN ($placeholders)";
        }

        // Handle multiple job types
        if (!empty($jobTypes)) {
            $placeholders = implode(',', array_fill(0, count($jobTypes), '?'));
            $query .= " AND jenis_pekerjaan IN ($placeholders)";
        }

        if (!empty($search)) {
            $query .= " AND posisi ILIKE ?";
        }

        switch ($sort) {
            case 'DESC':
                $query .= " ORDER BY created_at DESC";
                break;
            case 'ASC':
                $query .= " ORDER BY created_at ASC";
                break;
            default:
                break;
        }

        $query .= " LIMIT ? OFFSET ?";

        // Prepare the query
        $this->db->query($query);

        // Bind values for location types
        $index = 1;
        foreach ($locationTypes as $type) {
            $this->db->bind($index, $type);
            $index++;
        }

        // Bind values for job types
        foreach ($jobTypes as $type) {
            $this->db->bind($index, $type);
            $index++;
        }

        // Bind the search term if provided
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $this->db->bind($index, $searchTerm);
            $index++;
        }

        // Bind pagination
        $this->db->bind($index++, (int) $rowperpage, PDO::PARAM_INT);
        $this->db->bind($index, (int) $offset, PDO::PARAM_INT);

        $jobs = $this->db->resultSet();

        // Count query
        $countQuery = "SELECT COUNT(*) as total FROM lowongan WHERE 1=1";

        if (!empty($locationTypes)) {
            $placeholders = implode(',', array_fill(0, count($locationTypes), '?'));
            $countQuery .= " AND jenis_lokasi IN ($placeholders)";
        }

        if (!empty($jobTypes)) {
            $placeholders = implode(',', array_fill(0, count($jobTypes), '?'));
            $countQuery .= " AND jenis_pekerjaan IN ($placeholders)";
        }

        if (!empty($search)) {
            $countQuery .= " AND posisi ILIKE ?";
        }

        // Prepare count query
        $this->db->query($countQuery);

        // Bind values similarly to the main query
        $index = 1;
        foreach ($locationTypes as $type) {
            $this->db->bind($index, $type);
            $index++;
        }

        foreach ($jobTypes as $type) {
            $this->db->bind($index, $type);
            $index++;
        }

        if (!empty($search)) {
            $this->db->bind($index, $searchTerm);
        }

        $countResult = $this->db->single();

        $totalRow = $countResult ? $countResult['total'] : 0;
        $totalPages = ceil($totalRow / $rowperpage);

        return [
            'jobs' => $jobs,
            'total_pages' => $totalPages,
            'current_page' => $page
        ];
    }

    public function getAttachments($lowonganId)
    {
        $this->db->query("SELECT * FROM attachments_lowongan WHERE lowongan_id = :lowonganId");
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->resultSet();
    }
    public function getApplicationBrief($lowonganId)
    {
        $userId = $_SESSION['user_id'];

        $this->db->query("SELECT * FROM lamaran WHERE user_id = :userId AND lowongan_id = :lowonganId");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->single();
    }

    public function createJob($companyId, $posisi, $deskripsi, $jenisPekerjaan, $jenisLokasi, $attachments = []): mixed
    {
        $this->db->startTransaction();
        try {
            $this->db->query("INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi) 
                                VALUES (:companyId, :posisi, :deskripsi, :jenis_pekerjaan, :jenis_lokasi) 
                                RETURNING lowongan_id");
            $this->db->bind(':companyId', $companyId);
            $this->db->bind(':posisi', $posisi);
            $this->db->bind(':deskripsi', $deskripsi);
            $this->db->bind(':jenis_pekerjaan', $jenisPekerjaan);
            $this->db->bind(':jenis_lokasi', $jenisLokasi);

            $this->db->execute();
            $result = $this->db->getReturning();
            $lowonganId = $result['lowongan_id'];

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $this->db->query("INSERT INTO attachments_lowongan (lowongan_id, file_path) 
                                        VALUES (:lowonganId, :filePath)");
                    $this->db->bind(':lowonganId', $lowonganId);
                    $this->db->bind(':filePath', $attachment);

                    if (!$this->db->execute()) {
                        throw new Exception('Failed to insert attachment.');
                    }
                }
            }
            $this->db->commit();
            return $lowonganId;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getAllAppliedJobs()
    {
        $userId = $_SESSION['user_id'];
        $this->db->query("SELECT lowongan.*, users.nama as company_name 
            FROM lamaran 
            JOIN lowongan ON lamaran.lowongan_id = lowongan.lowongan_id 
            JOIN users ON lowongan.company_id = users.user_id 
            WHERE lamaran.user_id = :userId
            ORDER BY lamaran.created_at DESC
        ");
        $this->db->bind(':userId', $userId);

        return $this->db->resultSet();
    }

    public function getJobDetail($lowonganId)
    {
        $this->db->query("SELECT lowongan.*, users.nama as company_name,
                                    company_details.lokasi as lokasi,
                                    company_details.about as about
                            FROM lowongan 
                            JOIN users ON lowongan.company_id = users.user_id
                            JOIN company_details ON users.user_id = company_details.user_id
                            WHERE lowongan.lowongan_id = :lowonganId");
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->single();
    }

    public function getJobApplicants($lowonganId)
    {
        $this->db->query("SELECT users.nama as nama_pelamar,
                                 users.user_id as userid,
                                 lamaran.status as status_pelamar,
                                 lamaran.lowongan_id as lowongan_id
                                 FROM lamaran
                                 JOIN users ON lamaran.user_id = users.user_id
                                 WHERE lamaran.lowongan_id = :lowonganId");
        $this->db->bind(':lowonganId', $lowonganId);
        //echo(count($this->db->resultSet()));
        return $this->db->resultSet();
    }

    public function updateJobStatus($jobID, $newStatus)
    {
        $query = "UPDATE lowongan SET is_open = :newstatus WHERE lowongan_id = :jobID";
        $this->db->query($query);
        $this->db->bind(':newstatus', $newStatus, PDO::PARAM_BOOL);
        $this->db->bind(':jobID', $jobID);
        return $this->db->execute();
    }

    public function updateJobDetail($jobID, $posisi, $deskripsi, $jenisPekerjaan, $jenisLokasi, $attachments = [])
    {
        try {
            $this->db->query("UPDATE lowongan 
                                    SET posisi = :posisi, 
                                        deskripsi = :deskripsi, 
                                        jenis_pekerjaan = :jenisPekerjaan, 
                                        jenis_lokasi = :jenisLokasi 
                                    WHERE lowongan_id = :jobID
            ");
            $this->db->bind(':jobID', $jobID);
            $this->db->bind(':posisi', $posisi);
            $this->db->bind(':deskripsi', $deskripsi);
            $this->db->bind(':jenisPekerjaan', $jenisPekerjaan);
            $this->db->bind(':jenisLokasi', $jenisLokasi);

            $this->db->execute();
            $lowonganId = $jobID;

            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $this->db->query("INSERT INTO attachments_lowongan (lowongan_id, file_path) 
                                        VALUES (:lowonganId, :filePath)");
                    $this->db->bind(':lowonganId', $lowonganId);
                    $this->db->bind(':filePath', $attachment);

                    if (!$this->db->execute()) {
                        throw new Exception('Failed to insert attachment.');
                    }
                }
            }
            $this->db->commit();
            return $lowonganId;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
        // return $this->db->execute();
    }


    public function isRightCompany($lowonganId, $companyId)
    {
        $this->db->query('SELECT * FROM lowongan WHERE lowongan.lowongan_id = :lowonganId AND lowongan.company_id = :companyId');
        $this->db->bind(':lowonganId', $lowonganId);
        $this->db->bind(':companyId', $companyId);
        $data = $this->db->resultSet();
        return count($data) > 0;
    }

    public function deleteJob($lowonganId)
    {
        $this->db->query('DELETE FROM lowongan WHERE lowongan.lowongan_id = :lowonganId');
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->execute();
    }

    public function clearAttachments($lowonganId)
    {
        $this->db->query('DELETE FROM attachments_lowongan WHERE lowongan_id = :jobID');
        $this->db->bind(':jobID', $lowonganId);
        return $this->db->execute();
    }

    public function getApplicationsByJobId($jobId) {
        $this->db->query(
            "SELECT 
                u.nama AS applicant_name, 
                l.posisi AS job_position, 
                la.created_at AS application_date, 
                la.cv_path AS cv_url, 
                la.video_path AS video_url,  
                la.status AS application_status
            FROM lamaran la
            INNER JOIN users u ON la.user_id = u.user_id
            INNER JOIN lowongan l ON la.lowongan_id = l.lowongan_id
            WHERE la.lowongan_id = :jobId"
        );

        $this->db->bind(':jobId', $jobId);

        return $this->db->resultSet();
    }
}
