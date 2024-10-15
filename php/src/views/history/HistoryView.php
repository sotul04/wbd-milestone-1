<?php

class HistoryView implements ViewInterface
{
    private $data;
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function render()
    {
        require_once __DIR__. '/../../pages/history/HistoryPage.php';
    }
}