<?php

class ErrorController extends Controller implements ControllerInterface
{
    public function index()
    {
        $this->notfound();
    }

    public function notfound() {
        $view = $this->view('not-found', 'NotFoundView');
        $view->render();
    }

    public function unauthorized() {
        $view = $this->view('unauthorized', 'UnauthorizedView');
        $view->render();
    }
}