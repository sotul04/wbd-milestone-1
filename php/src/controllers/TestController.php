<?php

class TestController extends Controller implements ControllerInterface
{
    public function index()
    {
        $notFoundView = $this->view('test', 'TestView');
        $notFoundView->render();
    }
}