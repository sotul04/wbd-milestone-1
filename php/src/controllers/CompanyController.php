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
        if ($length === 3){
            if($params[2] === 'close'){
                //To-Do: Close lowongan
            }else if($params[2] === 'edit'){
                //To-Do: edit lowongan
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

    public function toggleJob($jobID){
        if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            $role = $this->getRole() ?? 'guest';
            if($role !== 'company'){
                json_response_fail('Unauthorized action!');
            }
    
            $companyId = $_SESSION['user_id'];
            if(!$this->model('JobModel')->isRightCompany($jobID, $companyId)){
                json_response_fail('Unlawful access!');
            }
    
            $status = $this->model('JobModel')->toggleJob($jobID);
            if($status === false ){
                json_response_fail('Something went wrong!');
            }else{
                json_response_success($status);
            }
        }
    }

    public function profile(){
        $role = $this->getRole() ?? 'guest';
        if ($role !== 'company'){
            $this->unauthorized();
        } 
        $companyId = $_SESSION['user_id'];
        $companyDetail = $this->model('CompanyDetailModel')->getCompanyByUserId($companyId); 

        if ($companyDetail === false){
            $this->notFound();
        }

        $profileView = $this->view('company', 'CompanyProfileView', ['companyDetail'=>$companyDetail]);
        $profileView->render();
    }
    // public function applicantDetail($jobID, $applicantID)
    // {
        
    // }
}