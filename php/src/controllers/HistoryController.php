<?php

class HistoryController extends Controller implements ControllerInterface
{
    public function index()
    {
        $homeview = $this->view('history', 'HistoryView');
        $homeview->render();
    }
}