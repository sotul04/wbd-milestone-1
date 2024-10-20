document.addEventListener("DOMContentLoaded", function () {
    const filters = ["locationType", "jobType", "sort"];
    const searchInput = document.getElementById("search");
    const jobListings = document.getElementById("job-listings");
    const pagination = document.getElementById("pagination");
    let debounceTimeout;

    filters.forEach((filter) => {
        const element = document.getElementById(filter);
        element.addEventListener("change", function () {
            applyFilters(1);
        });
    });

    searchInput.addEventListener("input", function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(function () {
            applyFilters(1);
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
            const value = document.getElementById(filter).value;
            if (value) {
                params.set(filter, value);
            } else {
                params.delete(filter);
            }
        });

        params.set('search', searchInput.value);
        params.set('page', page);
        let pathname = url.pathname.replace(/\/$/, '');
        let newUrl = `${pathname}?${params.toString()}`;

        history.pushState(null, '', newUrl);

        let endpoint = '/home/jobs';

        endpoint += `?${params.toString()}`;

        fetchData(endpoint);
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
                                    <a href="http://localhost:8000/job/${item.lowongan_id}" class="apply-btn">Detail</a>
                                </div>
                            </div>`;
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

    applyFilters(new URL(window.location.href).searchParams.get('page') || 1);

    window.addEventListener('popstate', function () {
        fetchData(window.location.href);
    });
});
