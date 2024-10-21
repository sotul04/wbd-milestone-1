document.addEventListener("DOMContentLoaded", function () {
    const filters = ["locationType", "jobType", "sort"];
    const searchInput = document.getElementById("search");
    const jobListings = document.getElementById("job-listings");
    const pagination = document.getElementById("pagination");
    let debounceTimeout;

    // Apply filter on change
    filters.forEach((filter) => {
        const element = document.getElementById(filter);
        element.addEventListener("change", function () {
            applyFilters(1); // Reset page to 1 when a filter changes
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

    // Pagination click handler
    // document.querySelectorAll('.pagination button').forEach(button => {
    //     button.addEventListener('click', function () {
    //         const page = this.getAttribute('data-page');
    //         if (page) {
    //             applyFilters(page); // Pass the selected page number
    //         }
    //     });
    // });

    function applyFilters(page) {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
    
        // Update filters in URL
        filters.forEach((filter) => {
            const value = document.getElementById(filter).value;
            if (value) {
                params.set(filter, value);
            } else {
                params.delete(filter);
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
                console.log("Length wkwk: " + jobs.length);
                
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
                                    <a href="http://localhost:8000/company/job/${item.lowongan_id}" class="apply-btn">Detail</a>
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
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        fetchData('/home/companyJobs?'+params.toString());
    });
});