<?php

class TestView implements ViewInterface
{
    public function __construct() {}

    public function render()
    {
        require_once __DIR__ . '/../../pages/test/TestPage.php';
    }
}