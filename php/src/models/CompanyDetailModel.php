<?php

class CompanyDetailModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCompanyByUserId($userId) {
        $this->db->query("SELECT * FROM company_details WHERE user_id = :userId");
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
}