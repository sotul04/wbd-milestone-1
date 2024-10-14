<?php

class App {
    protected $controller;
    protected $method;
    protected $params = [];
    private $db;

    public function __construct()
    {
        $this->method = 'index';

        $url = $this->parseUrl();

        $controllerRoot = $url[0] ?? null;
        $controllerRoot = ucfirst($controllerRoot);

        if (isset($controllerRoot) && file_exists(__DIR__ . '/../controllers/' . $controllerRoot . 'Controller.php')) {
            require_once __DIR__ . '/../controllers/' . $controllerRoot . 'Controller.php';
            $controllerClass = $controllerRoot . 'Controller';
            $this->controller = new $controllerClass();
        }
        else {
            header('Location: /home/');
        }
        unset($url[0]);

        $methodPart = $url[1] ?? null;
        if (isset($methodPart) && method_exists($this->controller, $methodPart)) {
            $this->method = $methodPart;
        }
        unset($url[1]);

        if (!empty($url)) {
            $this->params = array_values($url);
        }
        else {
            $this->params = [];
        }

        call_user_func_array([ $this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_SERVER['PATH_INFO']))
        {
            $url = trim($_SERVER['PATH_INFO'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            return $url;
        }
    }

}