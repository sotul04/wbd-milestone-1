<?php

class CompanyView implements ViewInterface
{
    private $data;
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function render()
    {
        require_once __DIR__. '/../../pages/company/CompanyPage.php';
    }
}