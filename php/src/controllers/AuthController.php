<?php

class AuthController extends Controller
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // hashing
            $password = password_hash($password, PASSWORD_DEFAULT);
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

            $user = $this->model('UserModel')->getUserById($email);
            if ($user === false) {
                echo "Username not found.\n";
                return;
            }
            $match = password_verify($password, $user["password"]);
            if ($match) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                echo "Login successfull.";
            } else {
                echo "Invalid username or password.";
            }
        }
    }

    public function logout()
    {
        session_destroy();
        json_response_success("success");
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
}