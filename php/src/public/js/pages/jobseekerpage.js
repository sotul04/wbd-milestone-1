document.addEventListener("DOMContentLoaded", function () {
    const filters = ["locationType", "jobType", "sort"];
    const searchInput = document.getElementById("search");
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
        debounceTimeout = setTimeout(function() {
            applyFilters(1); // Reset page to 1 when search changes
        }, 500);
    });

    // Pagination click handler
    document.querySelectorAll('.pagination button').forEach(button => {
        button.addEventListener('click', function () {
            const page = this.getAttribute('data-page');
            if (page) {
                applyFilters(page); // Pass the selected page number
            }
        });
    });

    // Function to apply filters and update URL
    function applyFilters(page) {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
    
        // Update filters in URL
        filters.forEach((filter) => {
            const value = document.getElementById(filter).value;
            if (value) {
                params.set(filter, value);
            } else {
                params.delete(filter);
            }
        });
    
        // Update search in URL
        const searchValue = searchInput.value.trim();
        if (searchValue) {
            params.set("search", searchValue);
        } else {
            params.delete("search");
        }
    
        // Update the page parameter in the URL
        params.set("page", page);
    
        // Remove trailing slash from pathname if it exists
        const pathname = url.pathname.endsWith('/') ? url.pathname.slice(0, -1) : url.pathname;
    
        // Update the URL with new parameters
        window.location.href = `${pathname}?${params.toString()}`;
    }
});
