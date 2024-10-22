<?php


class CompanyController extends Controller
{
    public function index(...$params)
    {
        $length = count($params);
        // goto not-found
        if ($length === 0)
            $this->notFound();
        if ($length === 1) {
            if($params[0] === 'profile'){
                //To-Do: Show Company Profile
                exit;
            } else if ($params[0] === 'toggleJob') {
                $this->toggleJob();
                exit;
            } else{
                $this->notFound();
            }
            exit;
        }
        if ($length === 2){
            if($params[1] === 'create'){
                //To-Do: Show the create new job page for company user + add new job to the database
            }else if($params[0] === 'job'){
                $this->jobDetail($params[1]);
                exit;
            }{
                //To-Do: Show the specific job created by user also all the applicants + update the specific job created by user (delete or close the job)
            }
        }
        if ($length === 3){
            if($params[2] === 'close'){
                //To-Do: Close lowongan
            }else if($params[2] === 'edit'){
                $this->toggleJob($params[1]);
            }
        }
        if ($length === 4){
            if($params[0] === 'job' && $params[2] === 'applicant'){
                $this->jobDetail($params[1]);
            }
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
        $attachments = $this->model('JobModel')->getAttachments($jobID);
        if ($role !== 'company') {
            $this->unauthorized();
        }else{
            //echo($jobID);
            $infoApplicants = $this->model('JobModel')->getJobApplicants($jobID);
            //echo('jumlah aplikan: ');
            //echo(count($infoApplicants));
            $detailView = $this->view('company', 'CompanyJobDetailView', ['role' => $role, 'jobDetail' => $jobDetail, 'attachments' => $attachments, 'infoApplicants' => $infoApplicants]);
            $detailView->render();
        }
    }
    public function toggleJob()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Get the JSON data from the request body
            $input = file_get_contents('php://input');
            $data = json_decode($input, true); // Decoding JSON into an associative array

            // Retrieve the jobId from the decoded data
            $jobId = $data['jobId'] ?? null;

            if (!$jobId) {
                json_response_fail('Missing jobId!');
            }

            $role = $this->getRole() ?? 'guest';
            if ($role !== 'company') {
                json_response_fail('Unauthorized action!');
                exit;
            }

            $companyId = $_SESSION['user_id'];
            if (!$this->model('JobModel')->isRightCompany($jobId, $companyId)) {
                json_response_fail('Unlawful access!');
                exit;
            }

            // Get the current job details
            $job = $this->model('JobModel')->getJobDetail($jobId);
            if ($job === false) {
                json_response_fail('Job not found!');
                exit;
            }

            $newStatus = TRUE;
            if($job['is_open'] === TRUE){
                $newStatus = FALSE;
            }

            // Update the job status
            $updated = $this->model('JobModel')->updateJobStatus($jobId, $newStatus);
            if ($updated === false) {
                json_response_fail('Something went wrong!');
                exit;
            }

            // Return the new job status as JSON
            json_response_success(['is_open' => $newStatus]);
        }
    }
}