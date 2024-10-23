document.addEventListener("DOMContentLoaded", function () {
    const saveButton = document.getElementById('save-button');
    const jobID = saveButton.getAttribute('job-id');
    const form = document.getElementById('editJobForm');
    const positionInput = document.getElementById('position');
    const descriptionInput = document.getElementById('description');
    const jenisPekerjaanInput = document.getElementById('jenisPekerjaan');
    const lokasiInput = document.getElementById('lokasi');

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const xhr = new XMLHttpRequest();
        xhr.open("PUT", 'http://localhost:8000/company/update-job', true);
        xhr.setRequestHeader("Content-Type", "application/json");

        // Create the body payload as a JSON object
        const requestBody = JSON.stringify({
            jobID: jobID,
            posisi: positionInput.value,
            deskripsi: descriptionInput.value,
            jenisPekerjaan: jenisPekerjaanInput.value,
            jenisLokasi: lokasiInput.value
        });

        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Toggle the status locally
                    window.location.href = 'http://localhost:8000/company/job ?>';
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
