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
                  WHERE lowongan.company_id = :companyId";

        if (!empty($locationType)) {
            $query .= " AND jenis_lokasi = :jenis_lokasi";
        }
        if (!empty($jobType)) {
            $query .= " AND jenis_pekerjaan = :jenis_pekerjaan";
        }
        if (!empty($search)) {
            $query .= " AND posisi ILIKE :search";
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
        $query .= " LIMIT :limit OFFSET :offset";
        
        $this->db->query($query);
        // Bind the parameter to the query
        $this->db->bind(':companyId', $companyId);

        if (!empty($locationType)) {
            $this->db->bind(':jenis_lokasi', $locationType);
        }
        if (!empty($jobType)) {
            $this->db->bind(':jenis_pekerjaan', $jobType);
        }
        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $this->db->bind(':search', $searchTerm);
        }

        $this->db->bind(':limit', $rowperpage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);

        $jobs = $this->db->resultSet();

        // Count query
        $countQuery = "SELECT COUNT(*) as total FROM lowongan JOIN users ON lowongan.company_id = users.user_id
                  WHERE lowongan.company_id = :companyId";

        if (!empty($locationType)) {
            $countQuery .= " AND jenis_lokasi = :jenis_lokasi";
        }
        if (!empty($jobType)) {
            $countQuery .= " AND jenis_pekerjaan = :jenis_pekerjaan";
        }
        if (!empty($search)) {
            $countQuery .= " AND posisi ILIKE :search";
        }

        $this->db->query($countQuery);
        $this->db->bind(':companyId', $companyId);

        if (!empty($locationType)) {
            $this->db->bind(':jenis_lokasi', $locationType);
        }
        if (!empty($jobType)) {
            $this->db->bind(':jenis_pekerjaan', $jobType);
        }
        if (!empty($search)) {
            $this->db->bind(':search', $searchTerm);
        }
        
        // Execute the query and fetch the result
        $countResult = $this->db->single();

        $totalRow = $countResult ? $countResult['total'] : 0; // Default to 0 if countResult is false

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

    public function createJob($companyId, $posisi, $deskripsi, $jenisPekerjaan, $jenisLokasi)
    {
        $this->db->query("INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi) 
                            VALUES (:companyId, :posisi, :deskripsi, :jenis_pekerjaan, :jenis_lokasi)");
        $this->db->bind(':companyId', $companyId);
        $this->db->bind(':posisi', $posisi);
        $this->db->bind(':deskripsi', $deskripsi);
        $this->db->bind(':jenis_pekerjaan', $jenisPekerjaan);
        $this->db->bind(':jenis_lokasi', $jenisLokasi);
        return $this->db->execute();
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
        //echo($jobID);
        //echo($newStatus);
        $query = "UPDATE lowongan SET is_open = :newstatus WHERE lowongan_id = :jobID";
        $this->db->query($query);
        $this->db->bind(':newstatus', $newStatus, PDO::PARAM_BOOL);
        $this->db->bind(':jobID', $jobID);
        return $this->db->execute();
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
}
