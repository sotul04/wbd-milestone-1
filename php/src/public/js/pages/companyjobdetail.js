document.addEventListener("DOMContentLoaded", function () {
    const statusIndicator = document.getElementById('status-indicator');
    const toggleButton = document.getElementById('toggle-button');
    const deleteButton = document.getElementById('delete-button');
    const jobId = toggleButton.getAttribute('data-job-id'); // Get jobId from data attribute

    toggleButton.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", `http://localhost:8000/company/toggleJob`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Toggle the status locally
                    const isOpen = statusIndicator.classList.contains('open');
                    
                    // Update the status indicator based on the current state
                    if (isOpen) {
                        statusIndicator.classList.remove('open');
                        statusIndicator.classList.add('close');
                        statusIndicator.innerHTML = `<p><strong>Closed</strong></p>`;
                        toggleButton.textContent = 'Open'; // Update button text
                        toggleButton.classList.remove('close');
                        toggleButton.classList.add('open');
                    } else {
                        statusIndicator.classList.remove('close');
                        statusIndicator.classList.add('open');
                        statusIndicator.innerHTML = `<p><strong>Open</strong></p>`;
                        toggleButton.textContent = 'Close'; // Update button text
                        toggleButton.classList.remove('open');
                        toggleButton.classList.add('close');
                    }

                    createToast("Data is successfully updated!", 'success');
                } else {
                    createToast("Something went wrong during update!", 'error');
                }
            } else {
                createToast("Request failed!", 'error');
            }
        };

        // Send the request body containing jobId
        xhr.send(requestBody);
    });

    function deleteJob(){
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", `http://localhost:8000/company/jobDelete`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        // Create the body payload as a JSON object
        const requestBody = JSON.stringify({
            jobId: jobId
        });

        xhr.onload = function () {
            if(xhr.status === 200){
                const response = JSON.parse(xhr.responseText);
                console.log(response);
                if(response.status === 'success'){
                    window.location.href = "http://localhost:8000/home";
                }else{
                    createToast(response.data, 'error');
                }
            }else {
                createToast("Request failed!", 'error');
            }
        };

        // Send the request body containing jobId
        xhr.send(requestBody);
    }

    deleteButton.addEventListener('click', () => {
        showModal('Job Deletion', 'Are you sure that you want to delete this job. All application data would be irreversibly lost!', deleteJob);
    });
});
