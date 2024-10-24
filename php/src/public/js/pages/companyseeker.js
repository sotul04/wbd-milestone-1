document.addEventListener("DOMContentLoaded", function () {
    const filters = ["locationType", "jobType", "sort"];
    const searchInput = document.getElementById("search");
    const jobListings = document.getElementById("job-listings");
    const pagination = document.getElementById("pagination");
    let debounceTimeout;

    function getCheckedValues(name) {
        return Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(el => el.value);
    }

    function setCheckedValues(name, values) {
        document.querySelectorAll(`input[name="${name}"]`).forEach(el => {
            el.checked = values.includes(el.value);
        });
    }

    filters.forEach((filter) => {
        if (filter !== "jobType" && filter !== "locationType") {
            const element = document.getElementById(filter);
            element.addEventListener("change", function () {
                applyFilters(1);
            });
        }
    });

    document.querySelectorAll('input[name="jobType"], input[name="locationType"]').forEach(el => {
        el.addEventListener('change', function () {
            applyFilters(1);
        });
    });

    // Debounce for search input
    searchInput.addEventListener("input", function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(function () {
            applyFilters(1); // Reset page to 1 when search changes
        }, 500);
    });

    function handlePagination() {
        document.querySelectorAll('.pagination button').forEach(button => {
            button.addEventListener('click', function () {
                const page = this.getAttribute('data-page');
                if (page) {
                    applyFilters(page);
                }
            });
        });
    }

    function applyFilters(page) {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        filters.forEach((filter) => {
            if (filter === 'jobType' || filter === 'locationType') {
                const values = getCheckedValues(filter);
                if (values.length > 0) {
                    params.set(filter, values.join(','));
                } else {
                    params.delete(filter);
                }
            } else {
                const value = document.getElementById(filter).value;
                if (value) {
                    params.set(filter, value);
                } else {
                    params.delete(filter);
                }
            }
        });

        // Update search parameter
        params.set('search', searchInput.value);

        // Update page parameter
        params.set('page', page);

        // Remove the trailing slash if present
        let pathname = url.pathname.replace(/\/$/, '');
        let newUrl = `${pathname}?${params.toString()}`;

        history.pushState(null, '', newUrl);

        let endpoint = '/home/companyJobs';

        endpoint += `?${params.toString()}`;

        fetchData(endpoint);
    }

    function deleteJob(jobID, element) {
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", `http://localhost:8000/company/job-delete`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        const requestBody = JSON.stringify({
            jobId: jobID
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(response);
                if (response.status === 'success') {
                    // let url = new URL(window.location.href);
                    // let params = new URLSearchParams(url.search);
                    // const currentPage = params.get('page');
                    element.classList.add('hidden');
                    createToast('Job deleted');
                    // applyFilters(currentPage);
                } else {
                    createToast(response.data, 'error');
                }
            } else {
                createToast("Request failed!", 'error');
            }
        };

        xhr.send(requestBody);
    }

    function fetchData(endpoint) {
        jobListings.innerHTML = '<div class="loading"><img class="animate-spin" alt="Loading Spin" src="http://localhost:8000/public/assets/icons/Loading.ico"> Loading</div>';
        pagination.innerHTML = '';

        const xhr = new XMLHttpRequest();
        xhr.open("GET", endpoint, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const jobs = response.data.jobs;

                if (jobs.length > 0) {
                    jobListings.innerHTML = '';
                    jobs.forEach(item => {
                        jobListings.innerHTML += `
                            <div class="job-card">
                                <div class="job-header">
                                    <h3 class="job-title">${item.posisi}</h3>
                                    <p class="company-name">${item.company_name}</p>
                                </div>
                                <div class="job-body">
                                    <p><strong>Type:</strong> ${item.jenis_pekerjaan}</p>
                                    <p><strong>Location:</strong> ${item.jenis_lokasi}</p>
                                </div>
                                <div class="job-footer">
                                    <p><small>Posted on: ${new Date(item.created_at).toLocaleDateString()}</small></p>
                                    <div class="buttons">
                                        <button job-id="${item.lowongan_id}" aria-label="Delete this job" class="btn btn-destroy delete-job">Delete</button>
                                        <a href="http://localhost:8000/company/job/${item.lowongan_id}" class="apply-btn">Detail</a>
                                    </div>
                                </div>
                            </div>`;
                    });

                    jobListings.querySelectorAll('div.job-card').forEach(item => {
                        const deleteButton = item.querySelector('button');
                        if (deleteButton) {
                            deleteButton.addEventListener('click', () => {
                                showModal('Job Deletion', 'Are you sure that you want to delete this job. All application data would be irreversibly lost!', () => deleteJob(deleteButton.getAttribute('job-id'), item));
                            });
                        }
                    });

                    let totalPages = response.data.total_pages;
                    let currentPage = response.data.current_page;

                    pagination.innerHTML = `<p>${currentPage} of ${totalPages}</p>`;

                    if (currentPage > 1) {
                        pagination.innerHTML += `<button data-page="${currentPage - 1}" class="prev-btn">Prev</button>`;
                    } else {
                        pagination.innerHTML += `<button disabled class="prev-btn">Prev</button>`;
                    }

                    for (let i = Math.max(1, currentPage - 1); i <= Math.min(totalPages, currentPage + 1); i++) {
                        if (i == currentPage) {
                            pagination.innerHTML += `<button class="active" disabled>${i}</button>`;
                        } else {
                            pagination.innerHTML += `<button data-page="${i}">${i}</button>`;
                        }
                    }

                    if (currentPage < totalPages) {
                        pagination.innerHTML += `<button data-page="${currentPage + 1}" class="next-btn">Next</button>`;
                    } else {
                        pagination.innerHTML += `<button disabled class="next-btn">Next</button>`;
                    }

                    handlePagination();
                } else {
                    jobListings.innerHTML = '<p>No Job Available</p>';
                }
            } else {
                console.error('Failed to fetch jobs:', xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Request error:', xhr.statusText);
        };

        xhr.send();
    }

    function applyParams() {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        if (params.has('search')) {
            searchInput.value = params.get('search');
        } else {
            searchInput.value = '';
        }

        if (params.has('sort')) {
            document.getElementById('sort').value = params.get('sort');
        } else {
            document.getElementById('sort').value = '';
        }

        if (params.has('jobType')) {
            setCheckedValues('jobType', params.get('jobType').split(','));
        } else {
            setCheckedValues('jobType', []);
        }

        if (params.has('locationType')) {
            setCheckedValues('locationType', params.get('locationType').split(','));
        } else {
            setCheckedValues('locationType', []);
        }
    }

    applyFilters(new URL(window.location.href).searchParams.get('page') || 1);

    window.addEventListener('popstate', function () {
        applyParams();
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        fetchData('/home/companyJobs?' + params.toString());
    });
});