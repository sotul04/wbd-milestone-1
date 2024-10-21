<?php

class JobController extends Controller
{
    public function index(...$params)
    {
        $length = count($params);
        // goto not-found
        if ($length === 0)
            $this->notFound();
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
        $attachments = $this->model('JobModel')->getAttachments($jobID);
        if ($role !== 'jobseeker') {
            $detailView = $this->view('job', 'JobDetailView', ['role' => $role, 'jobDetail' => $jobDetail, 'attachments' => $attachments]);
            $detailView->render();
        } else {
            $infoApplication = $this->model('JobModel')->getApplicationBrief($jobID);
            $detailView = $this->view('job', 'JobDetailView', ['role' => $role, 'jobDetail' => $jobDetail, 'attachments' => $attachments, 'infoApplication' => $infoApplication]);
            $detailView->render();
        }
    }

    public function jobApplication($jobID)
    {
        $jobDetail = $this->model('JobModel')->getJobDetail($jobID);
        if ($jobDetail === false) {
            $this->notFound();
        }
        $role = $this->getRole() ?? 'guest';
        if ($role !== 'jobseeker') {
            $this->unauthorized();
        }
        $infoApplication = $this->model('JobModel')->getApplicationBrief($jobID);
        if ($infoApplication !== false) {
            header('Location: /job/' . $jobID);
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $viewPage = $this->view('job', 'JobApplicationView', ['user_id' => $_SESSION['user_id'], 'jobID' => $jobID]);
            $viewPage->render();
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$jobDetail['is_open']) {
                json_response_fail('The job is closed. You can not apply this job.');
                exit;
            }
            $uploaderCv = new FileUploader('/../storage/files/cv'); 

            $userId = $_SESSION['user_id'];

            $cvResponse = $uploaderCv->uploadFile($_FILES['cv'], $userId, $jobID,['application/pdf'], 10000000); // 10MB max
            if ($cvResponse['status'] !== 'success') {
                json_response_fail($cvResponse['message']);
                return;
            }

            $videoResponse = ['status' => 'success', 'fileName' => null];

            if (isset($_FILES['video']) && $_FILES['video']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploaderVideo = new FileUploader('/../storage/files/videos');
                $videoResponse = $uploaderVideo->uploadFile($_FILES['video'], $userId, $jobID, ['video/mp4'], 50000000); // 50MB max

                if ($videoResponse['status'] !== 'success') {
                    json_response_fail($videoResponse['message']);
                    return;
                }
            }

            $applicationCreated = $this->model('ApplicationModel')->createApplication(
                $_SESSION['user_id'],
                $jobID,
                $cvResponse['fileName'],
                $videoResponse['fileName'] ?? null,
                'waiting',
                null
            );

            if ($applicationCreated) {
                json_response_success([
                    'message' => 'Application submitted successfully.',
                    'cvPath' => $cvResponse['fileName'],
                    'videoPath' => $videoResponse['fileName'] ?? null,
                    'user' => $_SESSION["name"],
                ]);
            } else {
                json_response_fail('Failed to save application data.');
            }
        }
    }
}