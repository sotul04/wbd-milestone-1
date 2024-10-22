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
            }else{
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
}