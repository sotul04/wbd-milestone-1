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
        $countQuery = "SELECT COUNT(*) as total FROM lowongan WHERE 1=1";

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

    public function getJobs($page, $sort, $locationType, $jobType, $search)
    {
        $rowperpage = ROWS_PER_PAGE;
        $offset = ($page - 1) * $rowperpage;

        // Updated query to join with users table to fetch company name
        $query = "SELECT lowongan.*, users.nama as company_name 
                FROM lowongan
                JOIN users ON lowongan.company_id = users.user_id
                WHERE 1=1";

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
        $countQuery = "SELECT COUNT(*) as total FROM lowongan WHERE 1=1";

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

    public function getJobDetail($lowonganId) {
        $this->db->query("SELECT lowongan.*, users.nama as company_name 
                            FROM lowongan 
                            JOIN users ON lowongan.company_id = users.user_id 
                            WHERE lowongan.lowongan_id = :lowonganId");
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->single();
    }
}
