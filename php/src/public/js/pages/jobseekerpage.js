document.addEventListener("DOMContentLoaded", function () {
    const filters = ["locationType", "jobType", "sort"];
    const searchInput = document.getElementById("search");
    let debounceTimeout;

    document.querySelectorAll('.job-description').forEach(editor => {
        // const description = editor.getAttribute('data-description');

        // Initialize Quill editor
        new Quill(editor, {
            theme: 'snow',
            modules: {
                toolbar: false // Disable the toolbar for read-only
            },
            readOnly: true // Set the editor to read-only
        });
    });

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

    // Pagination click handler
    document.querySelectorAll('.pagination button').forEach(button => {
        button.addEventListener('click', function () {
            const page = this.getAttribute('data-page');
            if (page) {
                applyFilters(page); // Pass the selected page number
            }
        });
    });

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
    
        // Redirect to updated URL
        window.location.href = `${pathname}?${params.toString()}`;
    }
});
