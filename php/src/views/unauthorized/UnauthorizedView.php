<?php

class UnauthorizedView implements ViewInterface
{
    public function __construct() {}

    public function render()
    {
        require_once __DIR__ . '/../../pages/unauthorized/UnauthorizedPage.php';
    }
}