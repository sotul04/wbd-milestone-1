<?php

class HomeController extends Controller implements ControllerInterface
{
    public function index()
    {
        // check filter params
        $page = $_GET['page'] ?? 1;
        $sort = $_GET['sort'] ?? '';
        $locationType = $_GET['locationType'] ?? '';
        $jobType = $_GET['jobType'] ?? '';
        $name = $_SESSION['name'] ?? null;
        $search = $_GET['search'] ?? '';
        $role = $this->getRole();
        $companyId = $_SESSION['user_id'] ?? null;

        // Validate parameters
        $page = $this->validatePage($page);
        $sort = $this->validateSort($sort);
        $locationType = $this->validateLocationType($locationType);
        $jobType = $this->validateJobType($jobType);
        $search = $this->validateSearch($search);

        if ($role === 'company') {
            $content = $this->model('JobModel')->getJobByCompanyId($companyId, $page, $sort, $locationType, $jobType, $search);
            $homeview = $this->view('home', 'CompanyHomeView', [
                'name' => $name,
                'page' => $page,
                'sort' => $sort,
                'locationType' => $locationType,
                'jobType' => $jobType,
                'search' => $search,
                'content' => $content
            ]);
            $homeview->render();
            exit;
        } else {
            $content = $this->model('JobModel')->getJobs($page, $sort, $locationType, $jobType, $search);
            $homeview = $this->view('home', 'JobseekerHomeView', [
                'name' => $name,
                'role' => $role,
                'page' => $page,
                'sort' => $sort,
                'locationType' => $locationType,
                'jobType' => $jobType,
                'search' => $search,
                'content' => $content
            ]);
            $homeview->render();
            exit;
        }
    }

    public function getRole() {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['role'];
        }
        return 'guest';
    }

    // Validation methods
    private function validatePage($page) {
        if (filter_var($page, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) === false) {
            return 1;
        }
        return (int) $page;
    }

    private function validateSort($sort) {
        $allowedSorts = ['ASC', 'DESC'];
        if (!in_array(strtoupper($sort), $allowedSorts)) {
            return '';
        }
        return strtoupper($sort);
    }

    private function validateLocationType($locationType) {
        $allowedLocationTypes = ['remote', 'on-site', 'hybrid'];
        if (!in_array(strtolower($locationType), $allowedLocationTypes)) {
            return '';
        }
        return strtolower($locationType);
    }

    private function validateJobType($jobType) {
        $allowedJobTypes = ['full-time', 'part-time', 'internship'];
        if (!in_array(strtolower($jobType), $allowedJobTypes)) {
            return ''; 
        }
        return strtoupper(substr($jobType,0,1)).strtolower(substr($jobType,1));
    }

    private function validateSearch($search) {
        return htmlspecialchars(substr($search, 0, 255));
    }
}
