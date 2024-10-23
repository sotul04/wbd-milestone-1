document.addEventListener("DOMContentLoaded", function () {
    const saveButton = document.getElementById('save-button');
    const companyId = saveButton.getAttribute('company-id');
    const form = document.getElementById('editForm');
    const nameInput = document.getElementById('name');
    const aboutInput = document.getElementById('about');
    const locationInput = document.getElementById('lokasi');

    function submitForm() {
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", 'http://localhost:8000/company/update-profile', true);
        xhr.setRequestHeader("Content-Type", "application/json");

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
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        showModal('Profile Update', 'Are you sure to save the changes? The change can not be undo.', submitForm);
    });
});
