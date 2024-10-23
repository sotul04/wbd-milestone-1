// Initialize Quill editor for job description
var quill = new Quill('#editor', {
    theme: 'snow',
    placeholder: 'Enter job description...',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, false] }],
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code-block'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }]
        ]
    }
});

function submitForm() {
    const jobID = document.getElementById('save-button').getAttribute('job-id');
    const positionInput = document.getElementById('position');
    const jenisPekerjaanInput = document.getElementById('jenisPekerjaan');
    const lokasiInput = document.getElementById('lokasi');
    const attachmentsInput = document.getElementById('attachments');

    const descriptionError = document.getElementById('description-error');
    const nameError = document.getElementById('position-error');

    const description = quill.root.innerHTML;

    if (positionInput.value.trim().length === 0) {
        nameError.classList.add('show-error');
        return;
    } else {
        nameError.classList.remove('show-error');
    }

    if (quill.getText().trim().length === 0) {
        descriptionError.classList.add('show-error');
        return;
    } else {
        descriptionError.classList.remove('show-error');
    }

    const formData = new FormData();
    formData.append('jobID', jobID);
    formData.append('posisi', positionInput.value);
    formData.append('deskripsi', description);
    formData.append('jenisPekerjaan', jenisPekerjaanInput.value);
    formData.append('jenisLokasi', lokasiInput.value);

    if (attachmentsInput.files.length > 0) {
        for (let i = 0; i < attachmentsInput.files.length; i++) {
            formData.append('attachments[]', attachmentsInput.files[i]);
        }
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost:8000/company/update-job', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                window.location.href = `http://localhost:8000/company/job/${response.data}`;
                createToast("Job updated successfully!", 'success');
            } else {
                createToast("Failed to update job: " + response.data, 'error');
            }
        } else {
            createToast("Failed to send request.", 'error');
        }
    };

    xhr.onerror = function () {
        createToast("Network error occurred.", 'error');
    };

    xhr.send(formData);
}

document.getElementById('editJobForm').addEventListener('submit', function (event) {
    event.preventDefault();

    showModal('Edit Job', 'Are you sure to edit this job? The change can not be undo.', submitForm);
});
