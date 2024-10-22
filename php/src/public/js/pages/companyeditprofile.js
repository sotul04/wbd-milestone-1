document.addEventListener("DOMContentLoaded", function () {
    const saveButton = document.getElementById('save-button');
    const companyId = saveButton.getAttribute('company-id');
    const form = document.getElementById('editForm');
    const nameInput = document.getElementById('name');
    const aboutInput = document.getElementById('about');
    const locationInput = document.getElementById('lokasi');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", 'http://localhost:8000/company/update-profile', true);
        xhr.setRequestHeader("Content-Type", "application/json");

        // Create the body payload as a JSON object
        const requestBody = JSON.stringify({
            userId: companyId,
            name: nameInput.value,
            about: aboutInput.value,
            location: locationInput.value
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Toggle the status locally
                    window.location.href = 'http://localhost:8000/company/profile';
                    createToast("Data is successfully updated!", 'success');
                } else {
                    createToast(response.data, 'error');
                }
            } else {
                createToast("Request failed!", 'error');
            }
        };

        xhr.send(requestBody);
    });
});
