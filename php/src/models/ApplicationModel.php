<?php

class ApplicationModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getApplicationById($lamaranId)
    {
        $this->db->query("SELECT * FROM lamaran WHERE lamaran_id = :lamaranId");
        $this->db->bind(':lamaranId', $lamaranId);
        return $this->db->single();
    }

    public function getApplicationByUserId($userId)
    {
        $this->db->query("SELECT * FROM lamaran WHERE user_id = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet();
    }

    public function createApplication($userId, $lowonganId, $cvPath, $videoPath, $status, $statusReason = '')
    {
        $this->db->query("INSERT INTO lamaran (user_id, lowongan_id, cv_path, video_path, status, status_reason) 
                          VALUES (:userId, :lowonganId, :cvPath, :videoPath, :status, :statusReason)");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':lowonganId', $lowonganId);
        $this->db->bind(':cvPath', $cvPath);
        $this->db->bind(':videoPath', $videoPath);
        $this->db->bind(':status', $status);
        $this->db->bind(':statusReason', $statusReason);
        return $this->db->execute();
    }

    public function isFileOwnedByJobseeker($userId, $fileName)
    {
        $this->db->query('SELECT * FROM lamaran WHERE user_id = :userID AND (cv_path = :cvPath OR video_path = :videoPath)');
        $this->db->bind(':userID', $userId);
        $this->db->bind(':cvPath', $fileName);
        $this->db->bind(':videoPath', $fileName);
        $data = $this->db->resultSet();
        return count($data) > 0;
    }

    public function isFileAccessedByCompany($companyId, $fileName)
    {
        $this->db->query("SELECT * FROM lamaran
            JOIN lowongan ON lamaran.lowongan_id = lowongan.lowongan_id
            WHERE lowongan.company_id = :companyID 
            AND (cv_path = :cvPath OR video_path = :videoPath)
        ");
        $this->db->bind(":companyID", $companyId);
        $this->db->bind(":cvPath", $fileName);
        $this->db->bind(":videoPath", $fileName);
        $data = $this->db->resultSet();

        return count($data) > 0;
    }

    public function isLowonganExists($userId, $lowonganId)
    {
        $this->db->query("SELECT * FROM lowongan WHERE company_id = :userId AND lowongan_id = :lowonganId");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':lowonganId', $lowonganId);
        $result = $this->db->resultSet();

        return !empty($result);
    }

    public function getApplication($jobID, $userID, $applicantID)
    {
        // Adjusted query to properly join tables using UUIDs and appropriate table aliases
        $this->db->query("SELECT lamaran.*, users.nama AS user_name, users.email AS user_email
                      FROM lamaran
                      JOIN lowongan ON lowongan.lowongan_id = lamaran.lowongan_id
                      JOIN users ON users.user_id = lamaran.user_id
                      WHERE lamaran.user_id = :applicantID
                      AND lamaran.lowongan_id = :jobID
                      AND lowongan.company_id = :companyID");

        // Bind the UUIDs (jobID, userID, and applicantID) to the query
        $this->db->bind(':applicantID', $applicantID);
        $this->db->bind(':jobID', $jobID);
        $this->db->bind(':companyID', $userID);

        // Return the single result (or false if no result is found)
        return $this->db->single();
    }

    public function updateApplication($jobID, $userID, $applicantID, $status = 'waiting', $reason = null)
    {
        // Retrieve the existing application using the getApplication function
        $data = $this->getApplication($jobID, $userID, $applicantID);

        // If no application is found, return false
        if ($data === false) {
            return false;
        }

        // Update the application status and reason in the lamaran table
        $this->db->query('UPDATE lamaran 
                      SET status = :status, status_reason = :reason
                      WHERE lamaran_id = :lamaranID');

        // Bind the values for the update query
        $this->db->bind(':status', $status);
        $this->db->bind(':reason', $reason);
        $this->db->bind(':lamaranID', $data['lamaran_id']); // Binding the UUID of the lamaran (application)

        // Execute the update query and return the result (true/false)
        return $this->db->execute();
    }

}
