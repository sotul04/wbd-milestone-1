document.addEventListener("DOMContentLoaded", function () {
    const saveButton = document.getElementById('save-button');
    const companyId = saveButton.getAttribute('company-id');

    saveButton.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", `http://localhost:8000/company/update-profile`, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        // Create the body payload as a JSON object
        const requestBody = JSON.stringify({
            userId: companyId
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Toggle the status locally
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
