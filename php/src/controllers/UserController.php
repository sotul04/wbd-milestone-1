<?php

class UserController extends Controller implements ControllerInterface 
{
    public function index() 
    {
        $notFoundView = $this->view('not-found', 'NotFoundView');
        $notFoundView->render();
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            http_response_code(301);
            header('Location: /user/logout');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $loginView = $this->view('user', 'LoginView');
            $loginView->render();
        }
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            http_response_code(301);
            header('Location: /home');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $loginView = $this->view('user', 'RegisterView');
            $loginView->render();
        }
    }
}