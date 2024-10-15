<?php

class AuthController extends Controller
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $name = $_POST['name'];
            $role = $_POST['role'];

            if ($this->model('UserModel')->createUser($email, $password, $role, $name)) {
                json_response_success(data: "success");
            } else {
                json_response_fail("fail");
            }
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Fetch user by email
            $user = $this->model('UserModel')->getUserByEmail($email);

            // Check if user exists
            if ($user === false) {
                // Render the view with error message if user is not found
                $loginView = $this->view('user', 'LoginView', ['errorMessage' => 'User not found.']);
                $loginView->render();
                return;
            }

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session and store user data
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect to home page upon successful login
                header('Location: /home');
                exit;
            } else {
                // Render the view with error message if password is incorrect
                $loginView = $this->view('user', 'LoginView', ['errorMessage' => 'Incorrect username or password.']);
                $loginView->render();
                return;
            }
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['role']);
        session_destroy();

        json_response_success("Logged out successfully");
    }

    public function info()
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_SESSION['user_id'])) {
            $userdata = $this->model('UserModel')->getUserById($_SESSION['user_id']);
            json_response_success($userdata);
        } else {
            json_response_fail(NOT_LOGGED_IN);
        }
    }

    public function isAdmin()
    {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                json_response_success('success');
            } else {
                json_response_fail('not');
            }
        } else {
            json_response_fail('not');
        }
    }

    public function checkAuth()
    {
        if (isset($_SESSION['user_id'])) {
            $response = [
                'user_id' => $_SESSION['user_id'],
                'role' => $_SESSION['role'],
                'name' => $_SESSION['name']
            ];
            json_response_success($response);
        } else {
            json_response_fail('User is not logged in.');
        }
    }
}