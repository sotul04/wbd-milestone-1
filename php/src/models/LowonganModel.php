<?php

class LowonganModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getLowonganById($lowonganId) {
        $this->db->query("SELECT * FROM lowongan WHERE lowongan_id = :lowonganId");
        $this->db->bind(':lowonganId', $lowonganId);
        return $this->db->single();
    }

    public function getLowonganByCompanyId($companyId) {
        $this->db->query("SELECT * FROM lowongan WHERE company_id = :companyId");
        $this->db->bind(':companyId', $companyId);
        return $this->db->resultSet();
    }

    public function createLowongan($companyId, $posisi, $deskripsi, $jenisPekerjaan, $jenisLokasi) {
        $this->db->query("INSERT INTO lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi) 
                          VALUES (:companyId, :posisi, :deskripsi, :jenis_pekerjaan, :jenis_lokasi)");
        $this->db->bind(':companyId', $companyId);
        $this->db->bind(':posisi', $posisi);
        $this->db->bind(':deskripsi', $deskripsi);
        $this->db->bind(':jenis_pekerjaan', $jenisPekerjaan);
        $this->db->bind(':jenis_lokasi', $jenisLokasi);
        return $this->db->execute();
    }
}
