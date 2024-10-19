<?php

class JobController extends Controller
{
    public function index(...$params)
    {
        $length = count($params);
        // goto not-found
        if ($length === 0) $this->notFound();
        if ($length === 1) {
            $this->jobDetail($params[0]);
            exit;
        }
        if ($params[1] === 'application') {
            $this->jobApplication($params[0]);
            exit;
        }
        $this->notFound();
    }

    public function jobDetail($jobID)
    {
        $jobDetail = $this->model('JobModel')->getJobDetail($jobID);
        if ($jobDetail === false) {
            $this->notFound();
        }
        $role = $this->getRole() ?? 'guest';
        $detailView = $this->view('job', 'JobDetailView', ['role' => $role, 'jobDetail' => $jobDetail]);
        $detailView->render();
    }

    public function jobApplication($jobID)
    {
        $jobDetail = $this->model('JobModel')->getJobDetail($jobID);
        if ($jobDetail === false) {
            $this->notFound();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        } else if ($_SERVER['REQUEST_METHOD'] === 'POST')  {

        }
    }
}