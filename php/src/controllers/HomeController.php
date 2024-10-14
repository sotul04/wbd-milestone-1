<?php

class HomeController extends Controller implements ControllerInterface
{
    public function index()
    {
        $homeview = $this->view('home', 'HomeView');
        $homeview->render();
    }
}