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

            if ($role === 'company') {
                $about = $_POST['about'];
                $location = $_POST['location'];
                $newUser = $this->model('UserModel')->createUser($email, $password, $role, $name, $location, $about);
                if ($newUser !== false && $newUser !== 'exists') {
                    $user = $this->model('UserModel')->getUserByEmail($email);
                    if ($user !== false) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['name'] = $user['nama'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        json_response_success(data: "success");
                    } else {
                        json_response_fail("Failed to login as new user");
                    }
                } else if ($newUser === 'exists'){
                    json_response_fail("The email has been used");
                } else {
                    json_response_fail("Failed to add new user");
                }
            } else {
                $newUser = $this->model('UserModel')->createUser($email, $password, $role, $name);
                if ($newUser !== false && $newUser !== 'exists') {
                    $user = $this->model('UserModel')->getUserByEmail($email);
                    if ($user !== false) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['name'] = $user['nama'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        json_response_success(data: "success");
                    } else {
                        json_response_fail("Failed to login as new user");
                    }
                } else if ($newUser === 'exists') {
                    json_response_fail("The email has been used");
                } else {
                    json_response_fail("Failed to add new user");
                }
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
                // Redirect to /user/login with error message and form data
                header('Location: /user/login?errorMessage=User%20not%20found&email=' . urlencode($email));
                exit;
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
                // Redirect to /user/login with error message and form data
                header('Location: /user/login?errorMessage=Incorrect%20username%20or%20password&email=' . urlencode($email));
                exit;
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

        header('Location: /user/login');
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