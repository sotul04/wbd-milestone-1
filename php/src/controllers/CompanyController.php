<?php


class CompanyController extends Controller implements ControllerInterface
{
    public function index()
    {
        $homeview = $this->view('company', 'CompanyView');
        $homeview->render();
    }
}