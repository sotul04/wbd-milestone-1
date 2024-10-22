<?php

class CompanyDetailModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCompanyByUserId($userId) {
        $this->db->query("
            SELECT u.nama, u.email, c.lokasi, c.about
            FROM company_details AS c
            JOIN users AS u ON c.user_id = u.user_id
            WHERE c.user_id = :userId
        ");
        $this->db->bind(':userId', $userId);
        return $this->db->single();
    }

    public function createCompanyDetail($userId, $lokasi, $about) {
        $this->db->query("INSERT INTO company_details (user_id, lokasi, about) VALUES (:user_id, :lokasi, :about)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':lokasi', $lokasi);
        $this->db->bind(':about', $about);
        return $this->db->execute();
    }

    public function updateCompanyDetail($userId, $lokasi, $about) {
        $this->db->query("UPDATE company_details SET lokasi = :lokasi, about = :about WHERE user_id = :userId");
        $this->db->bind(':userId', $userId);
        $this->db->bind(':lokasi', $lokasi);
        $this->db->bind(':about', $about);
        return $this->db->execute();
    }
}
