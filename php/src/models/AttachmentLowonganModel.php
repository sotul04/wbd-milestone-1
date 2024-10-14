<?php

class AttachmentLowonganModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAttachmentsByLowonganId($lowonganId) {
        $this->db->query("SELECT * FROM attachments_lowongan WHERE lowongan_id = :lowonganId");
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->resultSet();
    }

    public function createAttachment($lowonganId, $filePath) {
        $this->db->query("INSERT INTO attachments_lowongan (lowongan_id, file_path) 
                          VALUES (:lowonganId, :filePath)");
        $this->db->bind(':lowonganId', $lowonganId);
        $this->db->bind(':filePath', $filePath);
        return $this->db->execute();
    }
}
