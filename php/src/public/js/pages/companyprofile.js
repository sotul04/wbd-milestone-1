document.addEventListener("DOMContentLoaded", function () {
    const namaCompany = document.getElementById('nama-company');
    const lokasiCompany = document.getElementById('lokasi-company');
    const aboutCompany = document.getElementById('about-company');
    const editProfile = document.getElementById('edit-profile')

    toggleButton.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", `http://localhost:8000/company/update-profile`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        // Create the body payload as a JSON object
        const requestBody = JSON.stringify({
            jobId: jobId
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
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

        xhr.send(requestBody);
    });
});
