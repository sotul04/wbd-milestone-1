<?php

class NotFoundView implements ViewInterface
{
    public function __construct() {}

    public function render()
    {
        require_once __DIR__ . '/../../pages/not-found/NotFoundPage.php';
    }
}