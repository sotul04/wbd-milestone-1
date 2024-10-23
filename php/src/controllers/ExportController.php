<?php

class ExportController extends Controller implements ControllerInterface
{
    public function index()
    {
        header('Location: error/notfound');
        exit;
    }
    public function export()
    {
        $role = $this->getRole();
        if ($role !== 'company') {
            json_response_fail('Unauthorized access');
            exit;
        }

        $jobId = $_GET['jobid'];
        $companyId = $_SESSION['user_id'];

        if (!$this->model('JobModel')->isRightCompany($jobId, $companyId)) {
            json_response_fail('Unauthorized access');
            exit;
        }

        $jobApplications = $this->model('JobModel')->getApplicationsByJobId($jobId);

        json_response_success($jobApplications);
        exit;
    }
}
