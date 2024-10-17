<?php

class ApplicationModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getApplicationById($lamaranId) {
        $this->db->query("SELECT * FROM lamaran WHERE lamaran_id = :lamaranId");
        $this->db->bind(':lamaranId', $lamaranId);
        return $this->db->single();
    }

    public function getApplicationByUserId($userId) {
        $this->db->query("SELECT * FROM lamaran WHERE user_id = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet();
    }

    public function createApplication($userId, $lowonganId, $cvPath, $videoPath, $status, $statusReason = '') {
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
}
