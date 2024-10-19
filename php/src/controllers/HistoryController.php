<?php

class HistoryController extends Controller implements ControllerInterface
{
    public function index()
    {
        $role = $this->getRole();
        if ($role !== 'jobseeker') {
            $this->unauthorized();
        }
        $jobs = $this->model('JobModel')->getAllAppliedJobs();
        $homeview = $this->view('history', 'HistoryView', ['jobs' => $jobs]);
        $homeview->render();
    }
}