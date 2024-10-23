<?php

class CompanyAddJobView implements ViewInterface
{
    private $data;
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function render()
    {
        extract($this->data);
        require_once __DIR__. '/../../pages/company/CompanyAddJobPage.php';
    }
}