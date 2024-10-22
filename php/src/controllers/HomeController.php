<?php

class HomeController extends Controller implements ControllerInterface
{
    public function index()
    {
        // Check filter params
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
            //$content = $this->model('JobModel')->getJobByCompanyId($companyId, $page, $sort, $locationType, $jobType, $search);
            $homeview = $this->view('home', 'CompanyHomeView', [
                'name' => $name,
                'page' => $page,
                'sort' => $sort,
                'locationType' => implode(',', $locationType),
                'jobType' => implode(',', $jobType),
                'search' => $search
            ]);
            $homeview->render();
            exit;
        } else {
            $homeview = $this->view('home', 'JobseekerHomeView', [
                'name' => $name,
                'role' => $role,
                'page' => $page,
                'sort' => $sort,
                'locationType' => implode(',', $locationType),
                'jobType' => implode(',', $jobType),
                'search' => $search
            ]);
            $homeview->render();
            exit;
        }
    }

    public function jobs()
    {
        $page = $_GET['page'] ?? 1;
        $sort = $_GET['sort'] ?? '';
        $locationType = $_GET['locationType'] ?? '';
        $jobType = $_GET['jobType'] ?? '';
        $search = $_GET['search'] ?? '';
        $role = $this->getRole();

        $page = $this->validatePage($page);
        $sort = $this->validateSort($sort);
        $locationType = $this->validateLocationType($locationType);
        $jobType = $this->validateJobType($jobType);
        $search = $this->validateSearch($search);

        if ($role !== 'company') {
            $content = $this->model('JobModel')->getJobs($page, $sort, $locationType, $jobType, $search);
            json_response_success($content);
        }
    }

    public function companyJobs(){
        $page = $_GET['page'] ?? 1;
        $sort = $_GET['sort'] ?? '';
        $locationType = $_GET['locationType'] ?? '';
        $jobType = $_GET['jobType'] ?? '';
        $name = $_SESSION['name'] ?? null;
        $search = $_GET['search'] ?? '';
        $companyId = $_SESSION['user_id'] ?? null;
        $role = $this->getRole();

        // Validate parameters
        $page = $this->validatePage($page);
        $sort = $this->validateSort($sort);
        $locationType = $this->validateLocationType($locationType);
        $jobType = $this->validateJobType($jobType);
        $search = $this->validateSearch($search);

        if ($role === 'company') {
            $content = $this->model('JobModel')->getJobByCompanyId($companyId, $page, $sort, $locationType, $jobType, $search);;
            json_response_success($content);
        }
    }

    public function companyJobs(){
        $page = $_GET['page'] ?? 1;
        $sort = $_GET['sort'] ?? '';
        $locationType = $_GET['locationType'] ?? '';
        $jobType = $_GET['jobType'] ?? '';
        $name = $_SESSION['name'] ?? null;
        $search = $_GET['search'] ?? '';
        $companyId = $_SESSION['user_id'] ?? null;
        $role = $this->getRole();

        // Validate parameters
        $page = $this->validatePage($page);
        $sort = $this->validateSort($sort);
        $locationType = $this->validateLocationType($locationType);
        $jobType = $this->validateJobType($jobType);
        $search = $this->validateSearch($search);

        if ($role === 'company') {
            $content = $this->model('JobModel')->getJobByCompanyId($companyId, $page, $sort, $locationType, $jobType, $search);;
            json_response_success($content);
        }
    }

    public function getRole()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['role'];
        }
        return 'guest';
    }

    // Validation methods
    private function validatePage($page)
    {
        if (filter_var($page, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) === false) {
            return 1;
        }
        return (int) $page;
    }

    private function validateSort($sort)
    {
        $allowedSorts = ['ASC', 'DESC'];
        if (!in_array(strtoupper($sort), $allowedSorts)) {
            return '';
        }
        return strtoupper($sort);
    }

    private function validateLocationType($locationType)
    {
        if (!empty($locationType)) {
            $allowedLocationTypes = ['remote', 'on-site', 'hybrid'];
            $types = explode(',', strtolower($locationType));
            return array_filter($types, fn($type) => in_array($type, $allowedLocationTypes));
        }
        return [];
    }

    private function validateJobType($jobType)
    {
        if (!empty($jobType)) {
            $allowedJobTypes = ['full-time', 'part-time', 'internship'];
            $types = explode(',', strtolower($jobType));
            $data = array_filter($types, fn($type) => in_array($type, $allowedJobTypes));
            $data = array_map(fn($item) => ucfirst($item), $data);
            return $data;
        }
        return [];
    }

    private function validateSearch($search)
    {
        return htmlspecialchars(substr($search, 0, 255));
    }
}
