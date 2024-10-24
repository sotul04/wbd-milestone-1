<?php


class CompanyController extends Controller
{
    public function index(...$params)
    {
        $length = count($params);
        if ($length === 0)
            $this->notFound();
        if ($length === 1) {
            if ($params[0] === 'profile') {
                $this->profileShow();
            } else if ($params[0] === 'update-profile') {
                $this->updateProfile();
                exit;
            } else if ($params[0] === 'update-job') {
                $this->updateJob();
                exit;
            } else if ($params[0] === 'toggle-job') {
                $this->toggleJob();
                exit;
            } else if ($params[0] === 'job-delete') {
                $this->deleteJob();
                exit;
            } else if ($params[0] === 'job-create') {
                $this->addNewJob();
                exit;
            } else {
                $this->notFound();
            }
            exit;
        }
        if ($length === 2) {
            if ($params[0] === 'job' && $params[1] === 'create') {
                $this->createJob();
                exit;
            } else if ($params[0] === 'profile' && $params[1] === 'edit') {
                $this->editProfile();
                exit;
            } else if ($params[0] === 'job') {
                $this->jobDetail($params[1]);
                exit;
            }
        }
        if ($length === 3) {
            if ($params[0] === 'job' && $params[2] === 'edit') {
                $this->editJob($params[1]);
                exit;
            }
        }
        if ($length === 4) {
            if ($params[0] === 'job' && $params[2] === 'applicant') {
                $this->jobApplication($params[1], $params[3]);
                exit;
            }
        }
        $this->notFound();
    }

    private function jobDetail($jobID)
    {
        $jobDetail = $this->model('JobModel')->getJobDetail($jobID);
        if ($jobDetail === false) {
            $this->notFound();
        }
        $role = $this->getRole() ?? 'guest';
        $attachments = $this->model('JobModel')->getAttachments($jobID);
        if ($role !== 'company') {
            $this->unauthorized();
        } else {
            $infoApplicants = $this->model('JobModel')->getJobApplicants($jobID);
            $detailView = $this->view('company', 'CompanyJobDetailView', ['role' => $role, 'jobDetail' => $jobDetail, 'attachments' => $attachments, 'infoApplicants' => $infoApplicants]);
            $detailView->render();
        }
    }
    private function toggleJob()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Get the JSON data from the request body
            $input = file_get_contents('php://input');
            $data = json_decode($input, true); // Decoding JSON into an associative array

            // Retrieve the jobId from the decoded data
            $jobId = $data['jobId'] ?? null;

            // Error handling
            if (!$jobId) {
                json_response_fail('Missing jobId!');
                exit;
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
            if ($job['is_open'] === TRUE) {
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

    private function deleteJob()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Get the JSON data from the request body
            $input = file_get_contents('php://input');
            $data = json_decode($input, true); // Decoding JSON into an associative array

            // Retrieve the jobId from the decoded data
            $jobId = $data['jobId'] ?? null;

            // Error handling
            if (!$jobId) {
                json_response_fail('Missing jobId!');
                exit;
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

            $deleted = $this->model('JobModel')->deleteJob($jobId);
            if ($deleted === false) {
                json_response_fail('Something went wrong!');
                exit;
            }

            // Return the new job status as JSON
            json_response_success('Successfully deleted the job');
        }
    }


    private function editJob($jobID)
    {
        $jobDetail = $this->model('JobModel')->getJobDetail($jobID);
        if ($jobDetail === false) {
            $this->notFound();
        }
        $role = $this->getRole() ?? 'guest';
        $attachments = $this->model('JobModel')->getAttachments($jobID);
        if ($role !== 'company') {
            $this->unauthorized();
        } else {
            $detailView = $this->view('company', 'CompanyJobEditView', ['role' => $role, 'jobDetail' => $jobDetail, 'attachments' => $attachments]);
            $detailView->render();
        }
    }

    private function updateJob()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fileUploader = new FileUploader('/../public/files/attachments');
            $jobID = $_POST['jobID'] ?? null;

            if (!$jobID) {
                json_response_fail('Missing jobId!');
                exit;
            }

            $role = $this->getRole() ?? 'guest';
            if ($role !== 'company') {
                json_response_fail('Unauthorized action!');
                exit;
            }

            $companyId = $_SESSION['user_id'];
            if (!$this->model('JobModel')->isRightCompany($jobID, $companyId)) {
                json_response_fail('Unlawful access!' . $jobID);
                exit;
            }

            // Handle file uploads
            $uploadedFiles = [];
            if (isset($_FILES['attachments']) && $_FILES['attachments']['error'][0] != UPLOAD_ERR_NO_FILE) {
                foreach ($_FILES['attachments']['name'] as $key => $fileName) {
                    if ($_FILES['attachments']['error'][$key] !== UPLOAD_ERR_OK) {
                        json_response_fail('File upload error: ' . $_FILES['attachments']['error'][$key]);
                        exit;
                    }

                    $file = [
                        'name' => $_FILES['attachments']['name'][$key],
                        'type' => $_FILES['attachments']['type'][$key],
                        'tmp_name' => $_FILES['attachments']['tmp_name'][$key],
                        'error' => $_FILES['attachments']['error'][$key],
                        'size' => $_FILES['attachments']['size'][$key]
                    ];

                    $uploadResult = $fileUploader->uploadFile($file, ['image/jpeg', 'image/png', 'image/x-icon', 'image/webp'], 20 * 1024 * 1024);

                    if ($uploadResult['status'] === 'success') {
                        $uploadedFiles[] = $uploadResult['fileName'];
                    } else {
                        json_response_fail('Failed to upload file: ' . $uploadResult['message']);
                        exit;
                    }
                }
            }

            $result = $this->model('JobModel')->clearAttachments($jobID);

            if (!$result) {
                json_response_fail('Failed to clear the previous attachments');
                exit;
            }

            $updated = $this->model('JobModel')->updateJobDetail(
                $jobID,
                $_POST['posisi'],
                $_POST['deskripsi'],
                $_POST['jenisPekerjaan'],
                $_POST['jenisLokasi'],
                $uploadedFiles
            );

            if ($updated === false) {
                json_response_fail('Something went wrong!');
                exit;
            }

            json_response_success($updated);
            exit;
        }
    }

    private function profileShow()
    {
        $role = $this->getRole() ?? 'guest';
        if ($role !== 'company') {
            $this->unauthorized();
        }
        $companyId = $_SESSION['user_id'];
        $companyDetail = $this->model('CompanyDetailModel')->getCompanyByUserId($companyId);

        if ($companyDetail === false) {
            $this->notFound();
        }

        $profileView = $this->view('company', 'CompanyProfileView', ['companyDetail' => $companyDetail]);
        $profileView->render();
    }

    private function editProfile()
    {
        $role = $this->getRole() ?? 'guest';
        if ($role !== 'company') {
            $this->unauthorized();
        }
        $companyId = $_SESSION['user_id'];
        $companyDetail = $this->model('CompanyDetailModel')->getCompanyByUserId($companyId);

        if ($companyDetail === false) {
            $this->notFound();
        }

        $error = $_GET['error'] ?? null;
        $editProfileView = $this->view('company', 'CompanyEditView', ['companyDetail' => $companyDetail, 'error' => $error]);
        $editProfileView->render();
    }

    private function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $this->getRole();
            if ($role !== 'company') {
                header('Location: /error/unathorized');
                exit;
            }

            $companyId = $_SESSION['user_id'];

            $updated = $this->model('CompanyDetailModel')->updateCompanyDetail($companyId, $_POST['name'], $_POST['lokasi'], $_POST['about']);

            if ($updated === false) {
                header('Location: /company/profile/edit?error=Failed%20to%20update%20the%20data');
                exit;
            }

            $_SESSION['name'] = $_POST['name'];

            header('Location: /company/profile');
            exit;
        }
    }

    private function createJob()
    {
        $role = $this->getRole() ?? 'guest';
        if ($role !== 'company') {
            $this->unauthorized();
        }

        $createView = $this->view('company', 'CompanyAddJobView');
        $createView->render();
    }

    private function addNewJob()
    {
        $role = $this->getRole() ?? 'guest';
        if ($role !== 'company') {
            json_response_fail('Unauthorized services.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fileUploader = new FileUploader('/../public/files/attachments'); 
            $companyId = $_SESSION['user_id'];

            $posisi = $_POST['posisi'] ?? '';
            $deskripsi = $_POST['description'] ?? '';
            $jenisPekerjaan = $_POST['jenis_pekerjaan'] ?? '';
            $jenisLokasi = $_POST['jenis_lokasi'] ?? '';

            $uploadedFiles = [];
            if (isset($_FILES['attachments']) && $_FILES['attachments']['error'][0] != UPLOAD_ERR_NO_FILE) {
                foreach ($_FILES['attachments']['name'] as $key => $fileName) {
                    if ($_FILES['attachments']['error'][$key] !== UPLOAD_ERR_OK) {
                        json_response_fail('File upload error: ' . $_FILES['attachments']['error'][$key]);
                        exit;
                    }

                    $file = [
                        'name' => $_FILES['attachments']['name'][$key],
                        'type' => $_FILES['attachments']['type'][$key],
                        'tmp_name' => $_FILES['attachments']['tmp_name'][$key],
                        'error' => $_FILES['attachments']['error'][$key],
                        'size' => $_FILES['attachments']['size'][$key]
                    ];

                    $uploadResult = $fileUploader->uploadFile($file, ['image/jpeg', 'image/png'], 20 * 1024 * 1024);

                    if ($uploadResult['status'] === 'success') {
                        $uploadedFiles[] = $uploadResult['fileName'];
                    } else {
                        json_response_fail('Failed to upload file: ' . $uploadResult['message']);
                        exit;
                    }
                }
            }

            $jobCreated = $this->model('JobModel')->createJob($companyId, $posisi, $deskripsi, $jenisPekerjaan, $jenisLokasi, $uploadedFiles);

            if ($jobCreated !== false) {
                json_response_success($jobCreated);
            } else {
                json_response_fail('Failed to add new job.');
            }
        } else {
            json_response_fail('Invalid request method.');
        }
    }

    private function jobApplication($jobID, $applicantID)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $role = $this->getRole() ?? 'guest';
            if ($role !== 'company') {
                $this->unauthorized();
            }

            $userID = $_SESSION['user_id'];
            $data = $this->model('ApplicationModel')->getApplication($jobID, $userID, $applicantID);

            if ($data === false) {
                $this->notFound();
            }

            $view = $this->view('company', 'CompanyApplicationView', ['applicant' => $data]);
            $view->render();
        } else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $role = $this->getRole() ?? 'guest';
            if ($role !== 'company') {
                json_response_fail('Unauthorized request');
                exit;
            }

            $userID = $_SESSION['user_id'];
            $data = $this->model('ApplicationModel')->getApplication($jobID, $userID, $applicantID);

            if ($data === false) {
                json_response_fail('Application is not found');
                exit;
            }

            if($data['status'] !== 'waiting'){
                json_response_fail('Application status is already determined!');
                exit;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $result = $this->model('ApplicationModel')->updateApplication($jobID, $userID, $applicantID, $data['status'], $data['status_reason']);
            if ($result) {
                json_response_success('Successfully save the application status');
            } else {
                json_response_fail('Failed to save status');
            }
        }
    }

}