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
            header('Location: /home');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $errorMessage = isset($_GET['errorMessage']) ? $_GET['errorMessage'] : null;
            $email = isset($_GET['email']) ? $_GET['email'] : null;
    
            // Pass error message and email to the view
            $loginView = $this->view('user', 'LoginView', ['errorMessage' => $errorMessage, 'email' => $email]);
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
            $loginView = $this->view('user', 'RegisterView', ['title' => 'Register - Linkin']);
            $loginView->render();
        }
    }
}