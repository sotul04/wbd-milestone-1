<?php

class Controller
{
    public function view($folder, $view, $data = [])
    {
        require_once __DIR__ . '/../views/' . $folder . '/' . $view . '.php';
        return new $view($data);
    }

    public function model($model)
    {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }

    public function notFound()
    {
        $errorView = $this->view('not-found', 'NotFoundView');
        $errorView->render();
        exit;
    }

    public function unauthorized() {
        $errorView = $this->view('unauthorized', 'UnauthorizedView');
        $errorView->render();
        exit;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getRole() {
        if ($this->isLoggedIn()) {
            return $_SESSION['role'];
        } else return null;
    }
}